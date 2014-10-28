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

        // On récupère la liste des livres dont le délai de lecture est dépassé
        $booksDelayOutReader = $repo->findDelayOutReader();
        foreach($booksDelayOutReader as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject('Livre retiré')
                ->setFrom('contact.yourbooks@gmail.com')
                ->setTo($book->getReader()->getEmail())
                ->setBody("Vous avez dépassé le délai autorisé pour la lecture du livre '".$book->getTitle()."' ce livre vous a été retiré. ");

            $mailer->send($message);
        }

        // On récupère la liste des livres dont le délai de lecture ce termine dans 24h
        $booksSoonDelayOutReader = $repo->findSoonDelayOutReader();
        foreach($booksSoonDelayOutReader as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject('Fin de votre délai de lecture')
                ->setFrom('contact.yourbooks@gmail.com')
                ->setTo($book->getReader()->getEmail())
                ->setBody("
                            Bonjour ".$book->getReader()->getFirstName()." ".$book->getReader()->getLastName().",<br>
                            Vous avez confirmé le ".$book->getReceivedByReaderAt()." un manuscrit en lecture intitulé ".$book->getTitle().".<br>
                            Si vous avez déjà transmis votre fiche de lecture concernant ce manuscrit, merci de ne pas tenir compte de ce mail.<br>
                            Nous vous rappelons le cas échéant qu’il ne vous reste plus que 24 heures pour envoyer votre fiche de lecture de ce manuscrit.<br><br>
                            Passé ce délai, ainsi que précisé dans le contrat signé entre nous, ce manuscrit sera automatiquement réaffecté à un autre Lecteur
                            et la prestation considérée comme non effectuée.<br><br>
                            Une fois cette fiche remplie, merci de la transmettre pour validation à l’administration du site à partir de votre espace personnel.<br><br>
                            Nous restons à votre disposition<br><br>
                            L’équipe Your-books.");

            $mailer->send($message);
            $message = \Swift_Message::newInstance()
                ->setSubject('Fin du délai de lecture pour '.$book->getTitle())
                ->setFrom('contact.yourbooks@gmail.com')
                ->setTo('adm.yourbooks@gmail.com')
                ->setBody("
                            Bonjour admin,<br><br>
                            Nous vous rappelons qu’il ne reste plus qu'un jour au lecteur ".$book->getReader()." pour envoyer sa
                            critique sur le manuscrit ".$book->getTitle().".<br>
                            Pensez à effectuer les relances nécessaires.<br>
                            Your-books
                            ");

            $mailer->send($message);
        }

        // On récupère la liste des livres dont le délai de confirmation de réception est dépassé
        $booksDelayConfirmedReader = $repo->findDelayConfirmedReader();
        foreach($booksDelayConfirmedReader as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject('Délai de lecture dépassé')
                ->setFrom('contact.yourbooks@gmail.com')
                ->setTo($book->getReader()->getEmail())
                ->setBody("Vous avez dépassé le délai autorisé pour la confirmation de reception du livre '".$book->getTitle()."' ce livre peut vous être retiré. ");

            $mailer->send($message);
        }


        // On récupère la liste des livres dont le délai de rétractation est aujourd'hui
        $booksDelayOutRetracted = $repo->findDelayOutRetracted();
        foreach($booksDelayOutRetracted as $book){

            $mailer = $container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject('Nouveau manuscrit sorti de son délai de rétractation : '.$book->getTitle())
                ->setFrom('contact.yourbooks@gmail.com')
                ->setTo('adm.yourbooks@gmail.com')
                ->setBody("Bonjour admin,<br><br>

                    Un nouveau manuscrit transmis à Your-books est sorti de délai de rétractation.<br>
                    Il est à présent en attente de votre validation.<br><br>
                    Avant toute validation, vous devez effectuez une par une les six vérifications suivantes.<br><br>
                    IMPORTANT : si l’une de ces étapes ne peut-être validée, le manuscrit doit être rejeté,
                    supprimé de la base de donnée. Vous devez alors envoyer le mail de rejet à l’auteur
                    et procéder à son remboursement par chèque sous 45 jours à l’adresse postale indiquée
                    par l’auteur lors de son inscription.");

            $mailer->send($message);
        }


        // Envoie des mails
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