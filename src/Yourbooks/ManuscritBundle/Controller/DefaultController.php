<?php

namespace Yourbooks\ManuscritBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('YourbooksManuscritBundle:Default:index.html.twig', array('name' => $name));
    }
}
