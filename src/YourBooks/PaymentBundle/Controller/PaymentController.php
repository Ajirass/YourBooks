<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 24/03/2014
 * Time: 15:29
 */

namespace YourBooks\PaymentBundle\Controller;

use Exporter\Exception\InvalidMethodCallException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\PaymentBundle\Entity\Payment;

class PaymentController extends Controller
{
    /**
     * @param $userSalt
     * @param $bookId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function waitingPaymentAction($userSalt, $bookId)
    {
        return $this->render('YourBooksPaymentBundle:Payment:waitingPayment.html.twig', [
            'bookId' => $bookId,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Book $book
     * @throws \Exporter\Exception\InvalidMethodCallException
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @ParamConverter("book")
     */
    public function isPaidAjaxAction(Request $request, Book $book)
    {
        if(true === $request->isXmlHttpRequest())
            return new Response((int) $book->getPayed());
        else
            throw new InvalidMethodCallException('Only Ajax');
    }

    /**
     * @return Response
     */
    public function successAction()
    {
        return $this->render('YourBooksPaymentBundle:Payment:success.html.twig');
    }

//    public function treatmentAction(){
//        /* Script de Instant Payment Notification (IPN) ou Notification Instantanee de Paiement par Paypal */
//        /* Envoie un e-mail au vendeur quand Paypal a recu un paiement. Si la transaction est OK, Paypal se connecte a ce script et envoie des données, puis le script envoie un e-mail recapitulatif au vendeur.*/
//        /* Ajoutez l'URL de ce script lors de la creation d'un bouton Paypal ou dans les preferences de son compte Paypal a: Préférences de Notification instantanée de paiement. */
//        /* Derniere mise a jour: 15-02-2014 */
//
//        /* ADRESSE E-MAIL DU VENDEUR */
//        $emailto = "yourbooks_socity@gmail.com";
//        /* ADRESSE E-MAIL DE L'EMETTEUR DE CE MAIL (le FROM), DOIT ETRE UN VRAI COMPTE MAIL. */
//        $emailfrom = "godartrobin@gmail.com";
//        /* PREFIX AU SUJET DU MAIL POUR FILTRE ANTI-SPAM */
//        $sujetprefix = "[PAYPAL]";
//        /* CARACTERE SET DE VOTRE SITE WEB, METTRE utf-8 OU iso-8859-1 POUR BIEN AFFICHER LES CARACTERES ACCENTUES */
//        $charset = "utf-8";
//
//        /* NE RIEN MODIFIER CI-DESSOUS */
//
//        // lecture du post de PayPal et ajout de 'cmd'
//        $req = 'cmd=_notify-validate';
//
//        foreach ($_POST as $key => $value) {
//            $value = trim(urlencode(stripslashes($value)));
//            $req .= "&$key=$value";
//        }
//
//        // reponse a PayPal pour validation
//        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
//        $header .= "Host: www.paypal.com:80\r\n";
//        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
//
//        var_dump($_POST);
//        die();
//        // variables
//        $item_name = $_POST['item_name'];
//        $quantity = $_POST['quantity'];
//        $business = $_POST['business'];
//        $item_number = $_POST['item_number'];
//        $payment_status = $_POST['payment_status'];
//        $mc_gross = $_POST['mc_gross'];
//        $payment_currency = $_POST['mc_currency'];
//        $txn_id = $_POST['txn_id'];
//        $receiver_email = $_POST['receiver_email'];
//        $receiver_id = $_POST['receiver_id'];
//        $quantity = $_POST['quantity'];
//        $num_cart_items = $_POST['num_cart_items'];
//        $payment_date = $_POST['payment_date'];
//        $first_name = $_POST['first_name'];
//        $last_name = $_POST['last_name'];
//        $payment_type = $_POST['payment_type'];
//        $payment_status = $_POST['payment_status'];
//        $payment_gross = $_POST['payment_gross'];
//        $payment_fee = $_POST['payment_fee'];
//        $settle_amount = $_POST['settle_amount'];
//        $memo = $_POST['memo'];
//        $payer_email = $_POST['payer_email'];
//        $txn_type = $_POST['txn_type'];
//        $payer_status = $_POST['payer_status'];
//        $address_name = $_POST['address_name'];
//        $address_street = $_POST['address_street'];
//        $address_city = $_POST['address_city'];
//        $address_state = $_POST['address_state'];
//        $address_zip = $_POST['address_zip'];
//        $address_country = $_POST['address_country'];
//        $address_status = $_POST['address_status'];
//        $item_number = $_POST['item_number'];
//        $tax = $_POST['tax'];
//        $option_name1 = $_POST['option_name1'];
//        $option_selection1 = $_POST['option_selection1'];
//        $option_name2 = $_POST['option_name2'];
//        $option_selection2 = $_POST['option_selection2'];
//        $for_auction = $_POST['for_auction'];
//        $invoice = $_POST['invoice'];
//        $custom = $_POST['custom'];
//        $notify_version = $_POST['notify_version'];
//        $verify_sign = $_POST['verify_sign'];
//        $payer_business_name = $_POST['payer_business_name'];
//        $payer_id =$_POST['payer_id'];
//        $mc_currency = $_POST['mc_currency'];
//        $mc_fee = $_POST['mc_fee'];
//        $exchange_rate = $_POST['exchange_rate'];
//        $settle_currency  = $_POST['settle_currency'];
//        $parent_txn_id  = $_POST['parent_txn_id'];
//
//        if (!$fp) {
//        // HTTP ERROR
//        } else {
//            fputs ($fp, $header . $req);
//            while (!feof($fp)) {
//                $res = fgets ($fp, 1024);
//                if (strcmp ($res, "VERIFIED") == 0) {
//                    mail("godartrobin@gmail.com", 'paypal', $payment_date);
//
//        // Envoi du mail
//                    $mail_To = $emailto;
//                    $mail_Subject = $sujetprefix . " Paiement PAYPAL validé et verifié";
//                    $entetes  = "MIME-Version: 1.0 \n";
//                    $entetes .= "Content-Transfer-Encoding: 8bit \n";
//                    $entetes .= "Content-type: text/plain; charset=".$charset."\n";
//                    $entetes .= "Reply-To: ".$emailfrom."\n";
//                    $entetes .= "From: ".$emailfrom."\n";
//
//                    $mail_Body = "Paypal vient de valider et recevoir un paiement par carte bancaire. \nConnectez-vous à votre compte Paypal pour connaitre les détails de cette transaction et virer la somme sur votre compte. \nhttps://www.paypal.com/fr/";
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n====================================================";
//                    $mail_Body .= "\n" . "Voici quelques informations sur la transaction:\n";
//                    $mail_Body .= "\n" . "Transaction ID: " .  $txn_id ;
//                    $mail_Body .= "\n" . "Date de paiement: " . $payment_date;
//                    $mail_Body .= "\n" . "Etat du paiement: " . $payment_status;
//                    $mail_Body .= "\n====================================================";
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Attention, les informations ci-dessous peuvent être incomplètes.";
//                    $mail_Body .= "\n====================================================";
//                    $mail_Body .= "\n" . "Nombre d'objets dans le panier: " . $num_cart_items;
//                    if (strlen($item_name) > 1) { $mail_Body .= "\n\n" . "Objet en commande: " . $item_name . "\n" . "Numéro de l'objet: " . $item_number . " - " . "Quantité: " . $quantity; }
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Facture numéro: " . $invoice;
//                    $mail_Body .= "\n" . "Montant: " . $mc_gross . " " .$mc_currency;
//                    $mail_Body .= "\n" . "Frais Paypal: " . $mc_fee . " " .$mc_currency;
//                    $mail_Body .= "\n" . "Montant sur le compte: " . ($mc_gross - $mc_fee) . " " .$mc_currency;
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Nom: " . $first_name . " " .$last_name;
//                    $mail_Body .= "\n" . "Rue: " . $address_street;
//                    $mail_Body .= "\n" . "Code postal: " . $address_zip;
//                    $mail_Body .= "\n" . "Ville: " . $address_city;
//                    $mail_Body .= "\n" . "Etat et Pays: " . $address_state . " " .$address_country . " " .$address_country_code;
//                    $mail_Body .= "\n" . "Adresse e-mail: " . $payer_email;
//                    $mail_Body .= "\n" . "Nom de l'entreprise: " . $payer_business_name;
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Message du client: " . $memo;
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Statut Paypal du client: " . $payer_status;
//                    $mail_Body .= "\n====================================================";
//                    foreach ($_POST as $key => $value){
//                        $emailtext .= $key . " = " .$value ."\n";
//                    }
//                    mail($mail_To, $mail_Subject, $mail_Body . "\n\nVoici les données brutes reçues par Paypal: \n\n" . $emailtext, $entetes);
//                }
//                else if (strcmp ($res, "INVALID") == 0) {
//
//        // Envoi d'un mail si invalide
//                    $mail_To = $emailto;
//                    $mail_Subject = $sujetprefix . " Paiement PAYPAL NON VALIDE";
//                    $entetes  = "MIME-Version: 1.0 \n";
//                    $entetes .= "Content-Transfer-Encoding: 8bit \n";
//                    $entetes .= "Content-type: text/plain; charset=".$charset."\n";
//                    $entetes .= "Reply-To: ".$emailfrom."\n";
//                    $entetes .= "From: ".$emailfrom."\n";
//
//                    $mail_Body = "Un client a voulu payer par Paypal mais la transaction n'est pas valide. La commande est annulée. \nCe message est envoyé pour information, il n'y a rien à faire. \nhttps://www.paypal.com/fr/ \nCi-dessous, les données brutes envoyées par Paypal.";
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n====================================================";
//                    $mail_Body .= "\n" . "Nombre d'objets dans le panier: " . $num_cart_items;
//                    if (strlen($item_name) > 1) { $mail_Body .= "\n\n" . "Objet en commande: " . $item_name . "\n" . "Numéro de l'objet: " . $item_number . " - " . "Quantité: " . $quantity; }
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Facture numéro: " . $invoice;
//                    $mail_Body .= "\n" . "Montant: " . $mc_gross . " " .$mc_currency;
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Nom: " . $first_name . " " .$last_name;
//                    $mail_Body .= "\n" . "Rue: " . $address_street;
//                    $mail_Body .= "\n" . "Code postal: " . $address_zip;
//                    $mail_Body .= "\n" . "Ville: " . $address_city;
//                    $mail_Body .= "\n" . "Etat et Pays: " . $address_state . " " .$address_country . " " .$address_country_code;
//                    $mail_Body .= "\n" . "Adresse e-mail: " . $payer_email;
//                    $mail_Body .= "\n" . "Nom de l'entreprise: " . $payer_business_name;
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Message du client: " . $memo;
//                    $mail_Body .= "\n";
//                    $mail_Body .= "\n" . "Statut Paypal du client: " . $payer_status;
//                    $mail_Body .= "\n====================================================";
//                    foreach ($_POST as $key => $value){
//                        $emailtext .= $key . " = " .$value ."\n";
//                    }
//                    mail($mail_To, $mail_Subject, $mail_Body . "\n\nVoici les données brutes reçues par Paypal: \n\n" . $emailtext, $entetes);
//                }
//            }
//            fclose ($fp);
//        }
//        return $_POST;
//    }
//    public function successAction(){
//        return $this->render('YourBooksPaymentBundle:Main:success.html.twig');
//    }
} 