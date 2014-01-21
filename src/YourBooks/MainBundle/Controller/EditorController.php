<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;

class EditorController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function indexAction($category)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');
        $repoo = $em->getRepository('YourBooksBookBundle:BookFamilyCategory');

        if($category == null)
        {
            $books = $repo->findOnlyReading();
        }
        else
        {
            $books = $repo->findByCategory($category);
        }

        $familyCategories = $repoo->findAll();
        return $this->render('YourBooksMainBundle:Editor:homepage.html.twig', array(
            'books' => $books,
            'familyCategories' => $familyCategories,
        ));
    }

    public function downloadAction($id=null)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->find('YourBooksBookBundle:Book',$id);
        $path = $file->getAbsolutePath();

        $response = new Response();
        $response->setContent(file_get_contents($path));
        $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        $response->headers->set('Content-disposition', 'filename='. $file);

        return $response;

    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function historicAction()
    {
        $user = $this->getUser();
        $books = $user->getViewByEditor();
        return $this->render('YourBooksMainBundle:Editor:historique.html.twig', array(
            'books' => $books,
        ));
    }

    /**
     * Show the author profile
     */
    public function showAuthorProfileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('ApplicationSonataUserBundle:User',$id);

        return $this->container->get('templating')->renderResponse('YourBooksUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array('user' => $user));
    }
}