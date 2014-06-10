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
    protected $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }

    protected function sendEmail(UserInterface $user, $message, $subject)
    {
        $templateFile = "YourBooksMainBundle:Mail:email.html.twig";
        $templateContent = $this->twig->loadTemplate($templateFile);

        // Render the whole template including any layouts etc
        $body = $templateContent->render(array("message" => $message, "subject"=>$subject, "user"=>$user));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('mailbidon@gmail.com')
            ->setTo('godartrobin@gmail.com')
            //->setBody($message);
            ->setContentType('text/html')
            ->setBody($body);

        $this->mailer->send($message);
    }

    public function onMailEvent(MailEvent $event)
    {
            // On envoie un e-mail à l'utilisateur
            $this->sendEmail($event->getUser(), $event->getMessage(), $event->getSubject());

    }
} 