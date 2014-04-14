<?php

namespace YourBooks\PaypalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YourBooksPaypalBundle:index.html.twig');
    }
}
