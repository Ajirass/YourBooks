<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 21/02/2014
 * Time: 11:40
 */

namespace YourBooks\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YourBooks\BookBundle\Entity\Book;

class MailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('send:emails')
            ->setDescription("Envoie email avec gestion des délais (tache CRON)");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');
        $booksDelayOutReader = $repo->findDelayOutReader();
        foreach($booksDelayOutReader as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject($book->getReader()->getEmail())
                ->setFrom('godartrobin@gmail.com')
                ->setTo('godartrobin@gmail.com')
                ->setBody("Vous avez dépassé le délai autorisé pour la lecture du livre '".$book->getTitle()."' ce livre vous a été retiré. ");

            $mailer->send($message);
        }
        $booksSoonDelayOutReader = $repo->findSoonDelayOutReader();
        foreach($booksSoonDelayOutReader as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject($book->getReader()->getEmail())
                ->setFrom('godartrobin@gmail.com')
                ->setTo('godartrobin@gmail.com')
                ->setBody("Il vous reste 1 jour de délai autorisé pour la lecture du livre '".$book->getTitle()."' ce livre va vous a étre retiré autrement. ");

            $mailer->send($message);
            $message = \Swift_Message::newInstance()
                ->setSubject($book->getReader()->getEmail())
                ->setFrom('godartrobin@gmail.com')
                ->setTo('godartrobin@gmail.com')
                ->setBody("Il reste 1 jour au lecteur '".$book->getReader()."' pour lire le livre '".$book->getTitle()."'.");

            $mailer->send($message);
        }
        $booksDelayConfirmedReader = $repo->findDelayConfirmedReader();
        foreach($booksDelayConfirmedReader as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject($book->getReader()->getEmail())
                ->setFrom('godartrobin@gmail.com')
                ->setTo('godartrobin@gmail.com')
                ->setBody("Vous avez dépassé le délai autorisé pour la confirmation de reception du livre '".$book->getTitle()."' ce livre peut vous être retiré. ");

            $mailer->send($message);
        }
        if(isset($mailer)){
            $spool = $mailer->getTransport()->getSpool();
            $transport = $container->get('swiftmailer.transport.real');

            $spool->flushQueue($transport);
            $output->writeln('Envoyé!');
        }else{
            $output->writeln('Aucun message à envoyé!');
        }
    }
}