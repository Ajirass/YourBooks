<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use YourBooks\BookBundle\Entity\Book;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Application\Sonata\UserBundle\Entity\User;
use YourBooks\UserBundle\Form\RegisterType;
use YourBooks\MainBundle\Form\Type\ContactType;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            return new RedirectResponse($this->generateUrl('sonata_admin_redirect'));
        } elseif($this->get('security.context')->isGranted('ROLE_READER'))
        {
            return new RedirectResponse($this->generateUrl('your_books_main_reader_homepage'));
        } elseif ($this->get('security.context')->isGranted('ROLE_EDITOR'))
        {
            return new RedirectResponse($this->generateUrl('your_books_main_editor_homepage'));
        } elseif ($this->get('security.context')->isGranted('ROLE_AUTHOR'))
        {
            return new RedirectResponse($this->generateUrl('your_books_main_author_homepage'));
        }

        $user = new User();
        $registrationForm = $this->createForm(new RegisterType(), $user, array(
            'action' => $this->generateUrl('your_books_main_homepage'),
            'method' => 'post',
        ));
        $registrationForm->add('submit', 'submit', array('label' => 'Create'));


        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'your_books_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'your_books_user_registration_confirmed';
            }

            // On crée l'évènement avec ses 2 arguments
            $event = new UserRegisterEvent($user);

            // On déclenche l'évènement
            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch(ConfirmMailEvent::UserRegisterMail, $event);

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        return $this->render('YourBooksMainBundle:Main:homepage.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
            'registrationForm' => $registrationForm,
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Book $book
     *
     * @ParamConverter("book", class="YourBooksBookBundle:Book")
     * @Secure(roles="ROLE_EDITOR, ROLE_READER")
     */
    public function downloadBookAction(Book $book)
    {
        $user = $this->getUser();
        $readers = $book->getReaders();
        $readerValidation = $book->getReaderValidation();


        if($this->get('security.context')->isGranted('ROLE_ADMIN')){

        } elseif ($this->get('security.context')->isGranted('ROLE_READER')){
            if (false === $readers->contains($user) && false === $readerValidation )
                throw new AccessDeniedHttpException('Vous n\'avez pas les droits pour accéder à cette page.');
        } elseif ($this->get('security.context')->isGranted('ROLE_EDITOR')){
            if (false === $book->getReaderValidation())
                throw new AccessDeniedHttpException('Vous n\'avez pas accès à ce livre.');
        } else
            throw new AccessDeniedHttpException('Vous n\'avez pas accès à ce livre.');

        $path = $book->getAbsolutePath();

        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/pdf'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        $response->headers->set('Content-disposition', sprintf('attachment;filename="%s"', $path));
        //TODO modifier le nom
        //TODO

        return $response;
    }



    public function contactAction(Request $request)
    {
        //$em = $this->getDoctrine()->getEntityManager();
       // $repo = $em->getRepository('ApplicationSonataUserBundle:User');
        //$user = $repo->find(1);
        //$email = $user->getEmail();

        $form = $this->createForm(new ContactType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject($form->get('subject')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo('amelie.vanwaerbeke@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'YourBooksMainBundle:Mail:contact.html.twig',
                            array(
                                'name' => $form->get('name')->getData(),
                                'message' => $form->get('message')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', 'Votre e-mail a bien été envoyé, merci !');

                return $this->redirect($this->generateUrl('your_books_main_contact'));
            }
        }

        return $this->render('YourBooksMainBundle:Main:contact.html.twig', array(
            'form' => $form->createView()
        ));

    }
}