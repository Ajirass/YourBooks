<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 22/01/2014
 * Time: 15:41
 */

namespace YourBooks\BookBundle\Listener;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

class BookListener
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $ed;

    /**
     * @param EventDispatcher $ed
     */
    public function __construct(EventDispatcher $ed) {
        $this->ed = $ed;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();
        if ($entity instanceof Book) {
            if($eventArgs->hasChangedField('readerValidation') && $eventArgs->getNewValue('readerValidation') == true){

                $book_title = $eventArgs->getEntity()->getTitle();
                $user = $eventArgs->getEntity()->getAuthor();
                $subject = "Votre livre \"".$book_title."\" a été noté !";
                $datetime = $eventArgs->getEntity()->getReview()->getCreatedAt()->format('d/m/Y');
                //$createdAt = $eventArgs->getEntity()->getReview()->getCreatedAt()->format('d/m/Y');
                $message = "Bonjour ".$user->getUsername()."<br><br>
                    Nous avons le plaisir de vous informer que la fiche de lecture du manuscrit « $book_title » que vous nous avez adressée le « $datetime » a été validé par l’un de nos administrateurs.<br><br>
                    Cette fiche de lecture va être à present transmise à l’auteur du manuscrit ainsi qu’aux éditeurs.<br><br>
                    Cette validation déclenche la mise en attente de votre paiement. Nous vous rappelons que, pour nous puissions procéder à ce paiement, vous devez nous adresser dès aujourd’hui une facture de la prestation concernée.<br><br>
                    Pour cela, il vous suffit d’imprimer la pièce jointe à ce mail, de la remplir, de la signer et de nous la renvoyer. Votre paiement est dû à la fin du mois calendaire de reception par nos soins de ladite facture.<br><br>
                    Nous restons à votre disposition et vous remercions de votre collaboration<br><br>
                    L’équipe Your-books";

                // On crée l'évènement
                $event_readerValidation_author = new MailEvent($user, $message, $subject);

                $user = $eventArgs->getEntity()->getReader();
                $subject = "Fin de prestation pour le livre ".$book_title.".";
                $message = "
                    Bonjour ".$user->getUsername().",<br><br>
                    Le manuscrit ".$book_title." que vous avez transmis à Your-books le « $datetime »  a fait l’objet par un de nos lecteurs d’une fiche de lecture validée par notre administrateur.<br><br>
                    Veuillez trouver ci-dessous le contenu de cette fiche de lecture :<br><br>
                    <ul>
                        <li>
                            critère 1: ".$entity->getReview()->getCriteria1()."
                        </li>
                        <li>
                            critère 2: ".$entity->getReview()->getCriteria2()."
                        </li>
                        <li>
                            critère 3: ".$entity->getReview()->getCriteria3()."
                        </li>
                        <li>
                            critère 4: ".$entity->getReview()->getCriteria4()."
                        </li>
                        <li>
                            critère 5: ".$entity->getReview()->getCriteria5()."
                        </li>
                        <li>
                            note globale : ".$entity->getReview()->getNoteGlobale()."
                        </li>
                    </ul><br>

                    Le résumé de notre lecteur:<br>
                    ".$entity->getReview()->getSummary()."<br><br>
                    L’analyse de notre lecteur:<br>
                    ".$entity->getReview()->getCritic()."<br><br>

                    Nous vous rappelons qu’au terme de nos conditions générales, nos lecteurs sont des prestataires indépendants et que leur avis est à la fois souverain et non révisable par le même lecteur ou un autre.<br><br>

                    La validation de cette fiche de lecture a déclenché la transmission aux éditeurs des éléments suivants :<br><br>

                    (voir doc pour la suite des contenus)";
                                    // On crée l'évènement
                $event_readerValidation_reader = new MailEvent($user, $message, $subject);

                // On déclenche l'évènement
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation_author);
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation_reader);
            }
            if($eventArgs->hasChangedField('reader') && $eventArgs->getNewValue('reader') != false){
                $book = $eventArgs->getEntity();
                $user = $book->getReader();
                $entity->setSendToReaderAt(new \DateTime("now"));
                $em = $eventArgs->getEntityManager();
                $uow = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($meta, $entity);
                $message = "Bonjour ".$user->getFirstname()." ".$user->getLastname().",<br>
                            Un nouveau manuscrit est en attente de lecture.<br><br>
                            Merci de vous rendre dès à présent sur votre espace personnel et accuser bonne réception de ce manuscrit.<br>
                            Nous vous rappelons que vous disposez d’un délai de 48 heures à compter de la date d’envoi de ce mail pour accuser réception de ce manuscrit.<br>
                            Passé ce délai, le manuscrit sera automatiquement attribué à un autre lecteur.<br>
                            À compter de cet accusé de réception, vous disposez d’un délai de 18 jours pour lire le manuscrit et rédiger sa fiche de lecture
                            exclusivement au format fourni par Your-books sur votre espace personnel.<br>
                            Une fois cette fiche remplie, merci de la transmettre pour validation à l’administration du site à partir de votre espace personnel.<br>
                            Une fois votre fiche validée, vous recevrez un nouveau mail de confirmation.<br>
                            Nous vous souhaitons bonne lecture et restons à votre disposition.<br><br>

                            L’équipe Your-books. ";

                $subject = "Nouveau livre à noter";

                // On crée l'évènement
                $event_readerValidation = new MailEvent($user, $message, $subject);

                // On déclenche l'évènement
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation);
            }
            if($eventArgs->hasChangedField('reader') && $eventArgs->getNewValue('reader') == false){
                $entity->setSendToReaderAt(null);
                $entity->setReceivedByReaderAt(null);
                $em = $eventArgs->getEntityManager(false);
                $uow = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($meta, $entity);
            }

        }
    }
} 