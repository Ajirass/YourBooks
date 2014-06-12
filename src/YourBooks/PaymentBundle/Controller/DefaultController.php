<?php

namespace YourBooks\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YourBooksPaymentBundle:index.html.twig');
    }
}
