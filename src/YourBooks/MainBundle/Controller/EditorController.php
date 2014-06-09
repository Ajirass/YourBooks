<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use YourBooks\MainBundle\Form\Type\CategorySearchType;
use YourBooks\MainBundle\Form\Type\SearchType;

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
        $search_form = $this->container->get('form.factory')->create(new SearchType());
        $category_form = $this->container->get('form.factory')->create(new CategorySearchType());
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
            'searchform' => $search_form->createView(),
            'categoryform' => $category_form->createView(),
            'get_category' => $category,
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


    public function searchBookAction()
    {
        $category = null;
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $search = '';
            $search = $request->request->get('search');
            $em = $this->container->get('doctrine')->getEntityManager();

            if($search != '')
            {
                var_dump($search);
                $repo = $em->getRepository('YourBooksBookBundle:Book');

                $books = $repo->findBySearch($search);
            }
            else {
                $books = $em->getRepository('YourBooksBookBundle:Book')->findOnlyReading();
            }

            return $this->render('YourBooksMainBundle:Editor:list_books.html.twig', array(
                'books' => $books,
            ));
        }
        else {
            return $this->indexAction($category);
        }
    }

    public function searchBookCatAction()
    {
        $category = null;
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $search = '';
            $search = $request->request->get('search');
            $cat = $request->request->get('cat');
            $em = $this->container->get('doctrine')->getEntityManager();

            //if($search != '')
            //{
                $repo = $em->getRepository('YourBooksBookBundle:Book');

                $books = $repo->findBySearchCat($search, $cat);
            //}
            //else {
            //    $books = $em->getRepository('YourBooksBookBundle:Book')->findOnlyReading();
            //}

            return $this->render('YourBooksMainBundle:Editor:list_books.html.twig', array(
                'books' => $books,
            ));
        }
        else {
            return $this->indexAction($category);
        }
    }

    public function orderBookAction()
    {
        $category = null;
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $order = '';
            $order = $request->request->get('order');
            $params = $request->request->get('params');
            $em = $this->container->get('doctrine')->getEntityManager();

            if($params == "alphabetic"){
                $repo = $em->getRepository('YourBooksBookBundle:Book');
                $books = $repo->findByOrderAlphabetic($order);
            }

            if($params == "note"){
                $repo = $em->getRepository('YourBooksBookBundle:Book');
                $books = $repo->findByOrderNote($order);
            }

            if($params == "date"){
                $repo = $em->getRepository('YourBooksBookBundle:Book');
                $books = $repo->findByOrderDate($order);
            }

            return $this->render('YourBooksMainBundle:Editor:list_books.html.twig', array(
                'books' => $books,
            ));
        }
        else {
            return $this->indexAction($category);
        }
    }

    public function searchCategoryBookAction()
    {
        $category = null;
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $date = $request->request->get('date');
            $alphabetic = $request->request->get('alphabetic');
            $note = $request->request->get('note');
            $em = $this->container->get('doctrine')->getEntityManager();

            $repo = $em->getRepository('YourBooksBookBundle:Book');

            $books = $repo->findBySearch($alphabetic , $note, $date);


            return $this->render('YourBooksMainBundle:Editor:list_books.html.twig', array(
                'books' => $books,
            ));
        }
        else {
            return $this->indexAction($category);
        }
    }

    public function autoCompletionAction()
    {
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $search = $request->request->get('search');
            $em = $this->container->get('doctrine')->getEntityManager();

            $repo = $em->getRepository('YourBooksBookBundle:Book');

            $titles = $repo->autoCompletion($search);
            return $this->render('YourBooksMainBundle:Editor:auto_completion.html.twig', array(
                'titles' => $titles,
            ));
        }
    }

    public function autoCompletionCatAction()
    {
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $search = $request->request->get('search');
            $cat = $request->request->get('cat');
            $em = $this->container->get('doctrine')->getEntityManager();

            $repo = $em->getRepository('YourBooksBookBundle:Book');

            $titles = $repo->autoCompletionCat($search, $cat);
            return $this->render('YourBooksMainBundle:Editor:auto_completion.html.twig', array(
                'titles' => $titles,
            ));
        }
    }
}