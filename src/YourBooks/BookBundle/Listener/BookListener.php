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
    private $readerName;

    public function __construct(EventDispatcher $ed) {
        $this->ed = $ed;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Book) {
            if (is_object($entity->getReader()) && $entity->getReader() == false) {
                $this->readerName = $entity->getReader()->getUsername();
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Book) {
            if ($entity->getReader()) {
                if ($entity->getReader()->getUsername() !== $this->readerName) {
                    // Là on envoie le mail
                    $user = $entity->getReader();
                    $message = "Vous avez confirmé la reception du livre, vous avez 7 jours pour le lire.";
                    $subject = "Confirmation reception livre";
                    // On crée l'évènement
                    $event = new MailEvent($user, $message, $subject);

                    // On déclenche l'évènement
                    $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event);
                } else {
                }
            }
        }
    }
} 