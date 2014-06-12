<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 11/06/2014
 * Time: 11:16
 */

namespace YourBooks\UserBundle\Listener;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcher;
use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

class UserListener 
{
    private $ed;

    public function __construct(EventDispatcher $ed) {
        $this->ed = $ed;
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();
        if ($entity instanceof User) {
            if($eventArgs->hasChangedField('enabled') && $eventArgs->getNewValue('enabled') == true){
                $user = $eventArgs->getEntity();
                if(in_array('ROLE_READER', $user->getRoles())){
                    $subject = "Votre compte a été activé !";
                    $message = "Bonjour ".$user->getUsername()."<br><br>
                        Nous avons le plaisir de vous informer que nous avons bien reçu votre contrat signé. Nous venons de vous renvoyer votre exemplaire signé et tamponné.<br><br>
                        Votre compte est désormais actif.  Merci de surveiller régulièrement votre boîte mail ainsi que votre espace personnel afin de guetter l’arrivée de nouveaux manuscrits à lire.<br><br>
                        Nous vous remercions d’avoir choisi de collaborer avec Your-books.<br><br>
                        Nous restons à votre disposition.<br/><br/>
                        L’équipe. ";

                    // On crée l'évènement
                    $event = new MailEvent($user, $message, $subject);

                    // On déclenche l'évènement
                    $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event);
                }
                if(in_array('ROLE_EDITOR', $user->getRoles())){
                    $subject = "Votre compte a été activé !";
                    $message = "Bonjour ".$user->getUsername()."<br/><br/>
                        Votre espace personnel Your-books est désormais ouvert et actif.<br><br>
                        Nous restons à votre disposition.<br><br>
                        L’équipe Your-books";

                    // On crée l'évènement
                    $event = new MailEvent($user, $message, $subject);

                    // On déclenche l'évènement
                    $this->ed->dispatch(ConfirmMailEvent::onMailEvent, $event);
                }
            }
        }
    }
} 