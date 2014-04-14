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
                $message = "Votre livre à été noté il est désormais mis a disposition sur l'espace des éditeurs";
                $subject = "Votre livre à été noté";
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
                $message = "Vous avez reçu un nouveau livre a lire, vous avez 24h pour confirmer sa reception.";
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