<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YourBooks\BookBundle\Entity\Book;
use Application\Sonata\UserBundle\Entity\User;
use YourBooks\BookBundle\Form\Type\BookType;
use YourBooks\UserBundle\Form\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AuthorController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');

        $author = $this->getUser();
        $books = $repo->findByAuthor($author);

        $countBooksSubmit = $repo->countBooksSubmit($author);
        $countBooksRead = $repo->countBooksRead($author);

        $percent = (($countBooksRead)/($countBooksSubmit))*100;


        return $this->render('YourBooksMainBundle:Author:homepage.html.twig', array(
            'books' => $books,
            'countBooksSubmit' => $countBooksSubmit,
            'countBooksRead' => $countBooksRead,
            'percent' => $percent,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function sendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');

        $author = $this->getUser();
        $book = new Book();
        $book->setAuthor($author);

        $form = $this->createForm(new BookType(), $book);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        return $this->render('YourBooksMainBundle:Author:book_upload.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function parametersAction()
    {
        return $this->render('YourBooksMainBundle:Author:parameters.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function profileAction()
    {
        $user = $this->getUser();
        return $this->render('YourBooksMainBundle:Author:profile.html.twig', array(
            'user' => $user,
        ));
    }

    public function editProfileAction()
    {
        $user = $this->getUser();
        $form = $this->createForm(new ProfileType(), $user, array(
            'action' => $this->generateUrl('your_books_main_author_update_profile'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $this->render('YourBooksMainBundle:Author:profile-edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));


        //TODO recup user
        //TODO create form type with user
        //TODO return template with form
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function updateProfileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new ProfileType(), $user, array(
            'action' => $this->generateUrl('your_books_main_author_update_profile'),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('your_books_main_author_profile'));
        }

        return $this->render('YourBooksMainBundle:Author:profile-edit.html.twig', array(
            'user'   => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inscriptionAction(Request $request)
    {
        return $this->render('YourBooksMainBundle:Author:inscription.html.twig');
    }
}