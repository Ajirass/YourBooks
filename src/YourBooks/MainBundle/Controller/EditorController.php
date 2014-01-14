<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditorController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Secure(roles="ROLE_EDITOR")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');
        $repoo = $em->getRepository('YourBooksBookBundle:BookFamilyCategory');

        $books = $repo->findOnlyReading();
        $familyCategories = $repoo->findAll();
        return $this->render('YourBooksMainBundle:Editor:homepage.html.twig', array(
            'books' => $books,
            'familyCategories' => $familyCategories,
        ));
    }

    public function downloadAction($id=null)
    {
        $em = $this->getDoctrine()->getEntityManager();
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
    public function filterCategoryAction($category)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('YourBooksBookBundle:Book');
        $repoo = $em->getRepository('YourBooksBookBundle:BookFamilyCategory');

        $books = $repo->findByCategory($category);
        $familyCategories = $repoo->findAll();
        return $this->render('YourBooksMainBundle:Editor:filterCategory.html.twig', array(
            'books' => $books,
            'familyCategories' => $familyCategories,
            'idCategory' => $category,
        ));
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