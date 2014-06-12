<?php

namespace YourBooks\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaypalController extends Controller
{
    public function indexAction()
    {
        return $this->render('YourBooksPaymentBundle:Paypal:index.html.twig');
    }

    public function paymentAction()
    {

    }

    public function treatmentAction(Request $request)
    {
        /**
         * @var \Symfony\Bridge\Monolog\Logger
         */
        $logger = $this->get('logger');

        $logger->error('PaypalTreatment');
        $logger->error($request->__toString());
        $logger->error($request->headers);
        $logger->error($request->getMethod());

        $message = \Swift_Message::newInstance()
            ->setSubject('YourBooks')
            ->setFrom('thibaud.bardin+yourbooks@gmail.com')
            ->setTo('thibaud.bardin@gmail.com')
            ->setBody($request->__toString())
        ;
        $this->get('mailer')->send($message);

        return new Response($request);
    }
}
