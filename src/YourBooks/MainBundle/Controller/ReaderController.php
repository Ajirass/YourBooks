<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        /**
         * Old code
         *
         * $em = $this->getDoctrine()->getEntityManager();
         * $repo = $em->getRepository('YourBooksBookBundle:Book');
         * $reader = $this->getUser();
         * $books = $repo->booksReading($reader);
         * $booksReading = booksReading($reader);
         */
        $currentUser = $this->getUser();

        $bookRepo = $this->getDoctrine()->getManager()->getRepository('YourBooksBookBundle:Book');

        $bookReader = array();
        $books = $bookRepo->findAll();

        foreach($books as $book) {
            if (false === ($book->getSendByReader()) )
            {
                foreach($book->getReaders() as $reader) {
                    if ($reader === $currentUser)
                        $bookReader[] = $book;
                }
            }
        }

        return $this->render('YourBooksMainBundle:Reader:homepage.html.twig'
            , array(
                'books' => $bookReader,
                //'booksReading' => $booksReading,
        )
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
     *@param Book $book
     *
     * @ParamConverter("book", class="YourBooksBookBundle:Book")
     * @Secure(roles="ROLE_READER")
     */
    public function reviewAction(Request $request, Book $book)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('YourBooksBookBundle:BookReview');

        $reader = $this->getUser();

        $bookReview = new BookReview();
        $bookReview->setReader($reader);
        $bookReview->setBook($book);

        $form = $this->createForm(new BookReviewType(), $bookReview);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $book->setSendByReader(true);
            $em->persist($form->getData());
            $em->flush();

            return new RedirectResponse($this->generateUrl('your_books_main_reader_homepage'));
        }

        return $this->render('YourBooksMainBundle:Reader:review_upload.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *@param Book $book
     *
     * @ParamConverter("book", class="YourBooksBookBundle:Book")
     * @Secure(roles="ROLE_READER")
     */
    public function receivedAction(Book $book)
    {
        $em = $this->getDoctrine()->getManager();
        $book->setReceivedByReader(true);
        $em->flush();
        return new RedirectResponse($this->generateUrl('your_books_main_reader_homepage'));
    }


}