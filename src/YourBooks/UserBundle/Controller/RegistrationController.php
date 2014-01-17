<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YourBooks\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;

use YourBooks\MainBundle\ConfirmMail\ConfirmMailEvent;
use YourBooks\MainBundle\ConfirmMail\MailEvent;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends ContainerAware
{
    public function registerAction()
    {
        $account = $this->container->get('request')->query->get('account');
        if($account == 'reader')
        {
            $template = 'YourBooksUserBundle:Registration:register.html';
        }
        elseif($account == 'editor')
        {
            $template = 'YourBooksUserBundle:Registration:register.html';
        }
        else
        {
            $template = 'YourBooksMainBundle:Main:homepage.html';
        }

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();
            if($account == 'reader')
            {
                $roles = array("ROLE_READER");
                $subject = "Demande d'inscription lecteur";
                $message = "L'utilisateur ".$user->getUsername()." a fait une demande d'inscription en tant que Lecteur";
            }
            elseif($account == 'editor')
            {
                $roles = array("ROLE_EDITOR");
                $subject = "Demande d'inscription Editeur";
                $message = "L'utilisateur ".$user->getUsername()." a fait une demande d'inscription en tant que Editeur";
            }
            else
            {
                $roles = array("ROLE_AUTHOR");
            }

            if($account == 'reader' || $account == 'editor')
            {
                // On crée l'évènement
                $event = new MailEvent($user, $message, $subject);

                // On déclenche l'évènement
                $dispatcher = $this->container->get('event_dispatcher');
                $dispatcher->dispatch(ConfirmMailEvent::onMailEvent, $event);
            }

            $user->setRoles($roles);
            $this->container->get('fos_user.user_manager')->updateUser($user);
            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'your_books_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'your_books_main_homepage';
            }

            $this->setFlash('fos_user_success', 'Merci '.$user->getUsername().', votre inscription a bien été prise en compte. <br> Un mail de confirmation vous a été envoyé à l\'adresse '.$user->getEmail().' !');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse($template.'.'.$this->getEngine(), array(
            'form' => $form->createView(),
            'error' => '',
            'last_username' => '',
            'csrf_token' => '',
            'account'   => $account,
        ));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->container->get('templating')->renderResponse('YourBooksUserBundle:Registration:checkEmail.html.'.$this->getEngine(), array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setLastLogin(new \DateTime());
        $roles = $user->getRoles();
        if(in_array("ROLE_AUTHOR", $roles))
        {
            $user->setEnabled(true);
        }
        $this->container->get('fos_user.user_manager')->updateUser($user);
        if(in_array("ROLE_EDITOR", $roles) || in_array("ROLE_READER", $roles))
        {
            $this->setFlash('fos_user_success', 'Votre compte a été activé avec succes.<br>Cependant celui-ci requiert sa validation par l\'administrateur.  ');
            $route = 'your_books_main_homepage';
        }
        else
        {
            $this->setFlash('fos_user_success', 'Votre compte a été activé,<br> merci de compléter votre profil afin que les éditeurs puissent vous contacter');
            $route = 'your_books_user_profile_show';
        }
        $response = new RedirectResponse($this->container->get('router')->generate($route));
        if(in_array("ROLE_AUTHOR", $roles))
        {
            $this->authenticateUser($user, $response);
        }

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->container->get('templating')->renderResponse('YourBooksUserBundle:Registration:confirmed.html.'.$this->getEngine(), array(
            'user' => $user,
        ));
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param \FOS\UserBundle\Model\UserInterface        $user
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response);
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }
}
