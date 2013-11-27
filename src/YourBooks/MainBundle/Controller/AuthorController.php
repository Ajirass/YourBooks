<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\BookBundle\Form\Type\BookType;

class AuthorController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');

        $author = $this->getUser();
        $books = $repo->findByAuthor($author);

        $countBooksSubmit = $repo->countBooksSubmit($author);
        $countBooksRead = $repo->countBooksRead($author);

        $book = new Book();
        $form = $this->createForm(new BookType(), $book);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        return $this->render('YourBooksMainBundle:Author:homepage.html.twig', array(
            'books' => $books,
            'countBooksSubmit' => $countBooksSubmit,
            'countBooksRead' => $countBooksRead,

            'form' => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function sendAction()
    {
        return $this->render('YourBooksMainBundle:Author:book_upload.html.twig');
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
        return $this->render('YourBooksMainBundle:Author:profile.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inscriptionAction()
    {
        return $this->render('YourBooksMainBundle:Author:inscription.html.twig');
    }
}