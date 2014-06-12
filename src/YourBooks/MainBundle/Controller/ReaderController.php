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
use Application\Sonata\UserBundle\Entity\User;

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

            $subject = "Nouvelles notes envoyées";
            $message = "Bonjour ".$reader->getUsername().",<br>
                        Nous accusons réception de votre fiche de lecture, laquelle a bien été prise en compte.<br>
                         Nous vous remercions pour cet envoi. En voici un récapitulatif :
                        <ul>
                             <li>critère 1: ".$form->get('criteria1')->getData()."</li>
                             <li>critère 2: ".$form->get('criteria2')->getData()."</li>
                             <li>critère 3: ".$form->get('criteria3')->getData()."</li>
                             <li>critère 4: ".$form->get('criteria4')->getData()."</li>
                             <li>critère 5: ".$form->get('criteria5')->getData()."</li>
                             <li>".$note_globale."</li>
                        </ul>
                        <ul>
                            <li><p>Votre résumé: ".$form->get('summary')->getData()."</p></li>
                            <li><p>Votre analyse: ".$form->get('critic')->getData()."</p></li>
                        <ul>
                        Votre fiche de lecture est en cours de validation par un administrateur.<br><br>

                        Nous restons à votre disposition.<br><br>

                        L’équipe Your-books
                        ";
            // On crée l'évènement
            $event_reader = new MailEvent($reader, $message, $subject);

            // On déclenche l'évènement
            $this->get('event_dispatcher')
                ->dispatch(ConfirmMailEvent::onMailEvent, $event_reader);

            $subject = "Nouvelles notes envoyées";
            $message = "Bonjour admin,
                Une nouvelle fiche de lecture du lecteur (prénom et nom) pour le manuscrit (titre) est en attente de validation.
                Cliquez sur ce lien pour prendre connaissance de cette fiche de lecture : lien vers le manuscrit noté
                Attention : avant de valider cette fiche de lecture, vous devez vous assurer que son contenu respecte le contrat signé par le lecteur, en particulier mais pas seulement :
                (voir doc pour la suite des contenus)";

            $repo_user = $em->getRepository("ApplicationSonataUserBundle:User");
            $admin = $repo_user->find(143);

            // On crée l'évènement
            $event = new MailEvent($admin, $message, $subject);

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
        
        $user = $this->getUser();
        $message = "Vous avez confirmé la reception du livre, vous avez 18 jours pour le lire.";
        $subject = "Confirmation de réception d'un nouveau livre";
        // On crée l'évènement
        $event = new MailEvent($user, $message, $subject);

        // On déclenche l'évènement
        $this->get('event_dispatcher')
            ->dispatch(ConfirmMailEvent::onMailEvent, $event);

        $user = $book->getAuthor();
        $message = "Bonjour !<br>
                    Un lecteur vient d'accepter votre manuscrit, celui-ci est désormais en cours de lecture.<br>
                    Vous recevrez un récapitulatif de la fiche de lecture de votre manuscrit une fois que l'administrateur l'aura validé.<br><br>
                    L'équipe Your-Books
                    ";
        $subject = "Un lecteur a pris en charge votre manuscrit ".$book->getTitle();

        // On crée l'évènement
        $event = new MailEvent($user, $message, $subject);

        // On déclenche l'évènement
        $this->get('event_dispatcher')
            ->dispatch(ConfirmMailEvent::onMailEvent, $event);

        $repo_user = $em->getRepository("ApplicationSonataUserBundle:User");
        $admin = $repo_user->find(143);
        $message = "Bonjour admin,<br>
            Le lecteur ".$book->getReader()->getFirstname()." ".$book->getReader()->getLastname()." a bien accusé réception du manuscrit ".$book->getTitle().",<br><br>
            A compter de cette date, il dispose de 18 jours pour le lire et rédiger sa fiche de lecture.<br><br>
            Pensez le cas échéant au mail de relance 48 heures avant expiration de ce délai.<br><br>
            Vous n’avez pas d’autres actions à effectuer pour le moment concernant ce manuscrit.<br><br>
            Your-books";
        $subject = "Notification d'accusé de réception par le lecteur. ";

        // On crée l'évènement
        $event = new MailEvent($admin, $message, $subject);

        // On déclenche l'évènement
        $this->get('event_dispatcher')
            ->dispatch(ConfirmMailEvent::onMailEvent, $event);

        $book->setReceivedByReader(true);
        $book->setReceivedByReaderAt(new \DateTime("now"));
        $em->flush();

        return new RedirectResponse($this->generateUrl('your_books_main_reader_homepage'));
    }


}