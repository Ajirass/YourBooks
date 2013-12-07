<?php

namespace YourBooks\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use YourBooks\BookBundle\Entity\Book;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MainController extends Controller
{
    public function indexAction()
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



        return $this->render('YourBooksMainBundle:Main:homepage.html.twig');
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


        if($this->get('security.context')->isGranted('ROLE_ADMIN')){

        } elseif ($this->get('security.context')->isGranted('ROLE_READER')){
            if (false === $readers->contains($user))
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
}
