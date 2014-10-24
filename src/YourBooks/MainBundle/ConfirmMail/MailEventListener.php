<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/12/2013
 * Time: 15:54
 */

namespace YourBooks\MainBundle\ConfirmMail;

use Application\Sonata\UserBundle\Entity\User;
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

    protected function sendEmail(User $user, $message, $subject)
    {
        $templateFile = "YourBooksMainBundle:Mail:email.html.twig";
        $templateContent = $this->twig->loadTemplate($templateFile);


        $sendMail = \Swift_Message::newInstance();

        $imgUrl = $sendMail->embed(\Swift_Image::fromPath('ns506711.ip-192-99-2.net/bundles/yourbooksmain/images/logo_emailing.png'));

        // Render the whole template including any layouts etc
        $body = $templateContent->render(array("message" => $message, "subject"=>$subject, "user"=>$user, "imgUrl"=>$imgUrl));

        $email = $user->getEmail();
        //TODO changer le destinataire de l'email

        $sendMail
            ->setSubject($subject)
            ->setFrom(array('contact.yourbooks@gmail.fr' => 'Contact Your-Books'))
            ->setTo($email)
            ->setContentType('text/html')
            ->setBody($body);

        $this->mailer->send($sendMail);
    }

    public function onMailEvent(MailEvent $event)
    {
            // On envoie un e-mail à l'utilisateur
            $this->sendEmail($event->getUser(), $event->getMessage(), $event->getSubject());

    }
} 