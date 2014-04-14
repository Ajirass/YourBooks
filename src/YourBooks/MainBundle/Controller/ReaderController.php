<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\BookBundle\Entity\BookReview;
use YourBooks\BookBundle\Form\Type\BookReviewType;

use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;
use YourBooks\MainBundle\Twig\YourbooksExtension;

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

        return $this->render('YourBooksMainBundle:Reader:homepage.html.twig', array(
            'books' => $bookReader,
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
        if($book->getDownloadByReader() === false)
        {
            throw new AccessDeniedHttpException('Vous ne pouvez pas noter ce livre sans l\'avoir d\'abord téléchargé.');
        }
        elseif($book->getSendByReader() === true)
        {
            throw new AccessDeniedHttpException('Ce livre a déjà été noté.');
        }


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

        $note = (($form->get('criteria1')->getData()) + ($form->get('criteria2')->getData()) + ($form->get('criteria3')->getData()) + ($form->get('criteria4')->getData()) + ($form->get('criteria5')->getData())) / 5;
        $note_globale = round($note * 2.0) /2;

        $bookReview->setNoteGlobale($note_globale);
        $em->persist($form->getData());
        $em->flush();

            $message = "Une note a été envoyé par le lecteur ".$reader." vous devez validé ses notes.";
            $subject = "Nouvelle notes envoyé";
            // On crée l'évènement
            $event = new MailEvent($reader, $message, $subject);

            // On déclenche l'évènement
            $this->get('event_dispatcher')
                ->dispatch(ConfirmMailEvent::onMailEvent, $event);

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
        $book->setReceivedByReaderAt(new \DateTime("now"));
        $em->flush();
        $user = $this->getUser();
        $message = "Vous avez confirmé la reception du livre, vous avez 7 jours pour le lire.";
        $subject = "Confirmation reception livre";
        // On crée l'évènement
        $event = new MailEvent($user, $message, $subject);

        // On déclenche l'évènement
        $this->get('event_dispatcher')
            ->dispatch(ConfirmMailEvent::onMailEvent, $event);
        //$dispatcher = $this->container->get('event_dispatcher');
        //$dispatcher->dispatch(ConfirmMailEvent::onMailEvent, $event);

        return new RedirectResponse($this->generateUrl('your_books_main_reader_homepage'));
    }


}