<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EditorController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function indexAction()
    {
        return $this->render('YourBooksMainBundle:Editor:homepage.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function historicAction()
    {
        return $this->render('YourBooksMainBundle:Editor:historic.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inscriptionAction()
    {
        return $this->render('YourBooksMainBundle:Editor:inscription.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function parametersAction()
    {
        return $this->render('YourBooksMainBundle:Editor:parameters.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function profileAction()
    {
        return $this->render('YourBooksMainBundle:Editor:profile.html.twig');
    }
}