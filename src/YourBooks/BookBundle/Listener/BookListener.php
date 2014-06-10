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
use Symfony\Component\EventDispatcher\EventDispatcher;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

class BookListener
{
    private $ed;

    public function __construct(EventDispatcher $ed) {
        $this->ed = $ed;
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();
        if ($entity instanceof Book) {
            if($eventArgs->hasChangedField('readerValidation') && $eventArgs->getNewValue('readerValidation') == true){
                $user = $eventArgs->getEntity()->getReader();
                $subject = "Votre livre a été noté !";
                $message = "Votre livre a été noté, il est désormais mis a disposition sur l'espace des éditeurs.";
                // On crée l'évènement
                $event_readerValidation_author = new MailEvent($user, $message, $subject);

                $message = "Vos notes concernant le livre".$entity->getTitle()." ont été validé.";
                $subject = "Notes \"".$entity->getTitle()."\" validé";
                // On crée l'évènement
                $event_readerValidation_admin = new MailEvent($user, $message, $subject);

                // On déclenche l'évènement
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation_author);
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation_admin);
            }
            if($eventArgs->hasChangedField('reader') && $eventArgs->getNewValue('reader') != false){
                $book = $eventArgs->getEntity();
                $user = $book->getReader();
                $entity->setSendToReaderAt(new \DateTime("now"));
                $em = $eventArgs->getEntityManager();
                $uow = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($meta, $entity);
                $message = "Bonjour ".$user->getFirstname()." ".$user->getLastname().",
                            Un nouveau manuscrit est en attente de lecture.
                            Merci de vous rendre dès à présent sur votre espace personnel et accuser bonne réception de ce manuscrit.
                            Nous vous rappelons que vous disposez d’un délai de 48 heures à compter de la date d’envoi de ce mail pour accuser réception de ce manuscrit.
                            Passé ce délai, le manuscrit sera automatiquement attribué à un autre lecteur.
                            À compter de cet accusé de réception, vous disposez d’un délai de 18 jours pour lire le manuscrit et rédiger sa fiche de lecture
                            exclusivement au format fourni par Your-books sur votre espace personnel.
                            Une fois cette fiche remplie, merci de la transmettre pour validation à l’administration du site à partir de votre espace personnel.
                            Une fois votre fiche validée, vous recevrez un nouveau mail de confirmation.
                            Nous vous souhaitons bonne lecture et restons à votre disposition.

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