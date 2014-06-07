<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/12/2013
 * Time: 15:54
 */

namespace YourBooks\UserBundle\ConfirmMail;

use FOS\UserBundle\Model\UserInterface;
use YourBooks\UserBundle\ConfirmMail\UserRegisterEvent;

class UserRegistrationListener 
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    protected function sendEmail(UserInterface $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Confirmation inscription")
            ->setFrom('godartrobin@gmail.com')
            ->setTo('godartrobin@gmail.com')
            ->setBody("Bonjour '".$user->getUsername()."' votre inscription a bien été prise en compte. Bienvenue sur Yourbooks !");

        $this->mailer->send($message);
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
            // On envoie un e-mail à l'utilisateur
            $this->sendEmail($event->getUser());

    }
} 