<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\BookBundle\Entity\BookReview;
use YourBooks\BookBundle\Form\Type\BookReviewType;

class ReaderController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_READER")
     */
    public function indexAction()
    {
        //$em = $this->getDoctrine()->getEntityManager();
       // $repo = $em->getRepository('YourBooksBookBundle:Book');

      //  $reader = $this->getUser();
      //  $books = $repo->booksReading($reader);


       // $booksReading = booksReading($reader);


        return $this->render('YourBooksMainBundle:Reader:homepage.html.twig'
            //, array(
         //   'books' => $books,
            //'booksReading' => $booksReading,
     //   )
    );

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inscriptionAction()
    {
        return $this->render('YourBooksMainBundle:Main:inscription.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_READER")
     */
    public function parametersAction()
    {
        return $this->render('YourBooksMainBundle:Main:parameters.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_READER")
     */
    public function profileAction()
    {
        return $this->render('YourBooksMainBundle:Main:profile.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_READER")
     */
    public function reviewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('YourBooksBookBundle:BookReview');

        $reader = $this->getUser();
        $bookReview = new BookReview();
        $bookReview->setReader($reader);

        $form = $this->createForm(new BookReviewType(), $bookReview);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        return $this->render('YourBooksMainBundle:Reader:review_upload.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}