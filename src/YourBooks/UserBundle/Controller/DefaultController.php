<?php

namespace YourBooks\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('YourBooksUserBundle:Default:index.html.twig', array('name' => $name));
    }

    public function lockedAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $user->setLocked(true);
        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        $route = 'your_books_main_homepage';

        $request->getSession()->getFlashBag()->add('success', 'Votre compte a bien été désactivé.');
        $url = $this->container->get('router')->generate($route);
        $response = new RedirectResponse($url);
        return $response;
    }
}
