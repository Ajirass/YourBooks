<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/12/2013
 * Time: 15:54
 */

namespace YourBooks\MainBundle\ConfirmMail;

use FOS\UserBundle\Model\UserInterface;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

class MailEventListener
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    protected function sendEmail(UserInterface $user, $message, $subject)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('godartrobin@gmail.com')
            ->setTo('godartrobin@gmail.com')
            ->setBody($message);

        $this->mailer->send($message);
    }

    public function onMailEvent(MailEvent $event)
    {
            // On envoie un e-mail à l'utilisateur
            $this->sendEmail($event->getUser(), $event->getMessage(), $event->getSubject());

    }
} 