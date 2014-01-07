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

use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

class ReaderController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_READER")
     */
    public function indexAction()
    {
       
        $currentUser = $this->getUser();

        $bookReader = $currentUser->getBooks();

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
            'book' => $book,
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
        $user = $this->getUser();
        $message = "Vous avez confirmez la reception du livre, vous avez 7jours pour le lire.";
        // On crée l'évènement
        $event = new MailEvent($user, $message);

        // On déclenche l'évènement
        $this->get('event_dispatcher')
            ->dispatch(ConfirmMailEvent::onMailEvent, $event);
        //$dispatcher = $this->container->get('event_dispatcher');
        //$dispatcher->dispatch(ConfirmMailEvent::onMailEvent, $event);

        return new RedirectResponse($this->generateUrl('your_books_main_reader_homepage'));
    }


}