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
                ->setBody("
                            Bonjour ".$book->getReader().",

                            Vous avez confirmé le ".$book->getReceivedByReaderAt()." un manuscrit en lecture intitulé ".$book->getTitle().".

                            Si vous avez déjà transmis votre fiche de lecture concernant ce manuscrit, merci de ne pas tenir compte de ce mail.

                            Nous vous rappelons le cas échéant qu’il ne vous reste plus que 24 heures pour envoyer votre fiche de lecture de ce manuscrit

                            Passé ce délai, ainsi que précisé dans le contrat signé entre nous, ce manuscrit sera automatiquement réaffecté à un autre Lecteur
                            et la prestation considérée comme non effectuée.

                            Une fois cette fiche remplie, merci de la transmettre pour validation à l’administration du site à partir de votre espace personnel.

                            Nous restons à votre disposition

                            L’équipe Your-books.");

            $mailer->send($message);
            $message = \Swift_Message::newInstance()
                ->setSubject($book->getReader()->getEmail())
                ->setFrom('godartrobin@gmail.com')
                ->setTo('godartrobin@gmail.com')
                ->setBody("
                            Bonjour admin,

                            Nous vous rappelons qu’il ne reste plus qu'un jour au lecteur ".$book->getReader()." pour envoyer sa
                            critique sur le manuscrit ".$book->getTitle().".

                            Pensez à effectuer les relances nécessaires

                            Your-books
                            ");

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
            $output->writeln('Aucun message à envoyer !');
        }
    }
}