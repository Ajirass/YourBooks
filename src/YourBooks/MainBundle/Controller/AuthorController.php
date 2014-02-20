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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

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
        $books = $repo->findByAuthor($author, array('createdAt' => 'DESC'));

        $countBooksSubmit = $repo->countBooksSubmit($author);
        $countBooksRead = $repo->countBooksRead($author);

        if(($countBooksSubmit) > 0)
        {
            $percent = round((($countBooksRead)/($countBooksSubmit))*100);
        }
        else
        {
            $percent = 0;
        }

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

            $message_author = "Manuscrit envoyé";
            $subject_author = "Envoi de votre manuscrit";
            // On crée l'évènement
            $event_author = new MailEvent($author, $message_author, $subject_author);

            // On déclenche l'évènement
            $this->get('event_dispatcher')
                ->dispatch(ConfirmMailEvent::onMailEvent, $event_author);

            $message_admin = "Nouveau manuscrit envoyé";
            $subject_admin = "Un manuscrit a été envoyé par l'auteur ".$author->getUsername();
            // On crée l'évènement
            $event_admin = new MailEvent($author, $message_admin, $subject_admin);

            // On déclenche l'évènement
            $this->get('event_dispatcher')
                ->dispatch(ConfirmMailEvent::onMailEvent, $event_admin);

            $request->getSession()->getFlashBag()->add('success', 'Manuscrit envoyé avec succes');

            return $this->redirect($this->generateUrl('your_books_main_author_homepage'));
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function deleteBookAction(Request $request, Book $book)
    {
        $dateNow = new \DateTime();
        if(($dateNow->diff($book->getCreatedAt())->format('%a')) > 7)
        {
            throw new AccessDeniedHttpException('Votre délai de rétractation est écoulé : vous ne pouvez plus supprimer ce livre.');
        }
        else
        {
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Manuscrit supprimé avec succes');

        return $this->redirect($this->generateUrl('your_books_main_author_homepage'));
        }
    }
}