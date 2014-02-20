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
    private $readerValidation;

    public function __construct(EventDispatcher $ed) {
        $this->ed = $ed;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Book) {
            if($args->hasChangedField('readerValidation') && $args->getNewValue('readerValidation') == true){
                $user = $args->getEntity()->getReader();
                $message = "Votre livre à été noté il est désormais mis a disposition sur l'espace des éditeurs";
                $subject = "Votre livre à été noté";
                // On crée l'évènement
                $event_readerValidation = new MailEvent($user, $message, $subject);

                // On déclenche l'évènement
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation);
            }
            if($args->hasChangedField('reader') && $args->getNewValue('reader') != false){
                $user = $args->getEntity()->getReader();
                $message = "Vous avez reçu un nouveau livre a lire, vous avez 7 jours pour confirmer sa reception.";
                $subject = "Nouveau livre à noter";
                // On crée l'évènement
                $event_readerValidation = new MailEvent($user, $message, $subject);

                // On déclenche l'évènement
                $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event_readerValidation);
            }
        }
    }
} 