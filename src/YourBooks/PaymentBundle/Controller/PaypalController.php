<?php

namespace YourBooks\PaymentBundle\Controller;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use YourBooks\BookBundle\Entity\Book;
use YourBooks\PaymentBundle\Entity\Payment;

class PaypalController extends Controller
{
    /**
     * @param Request $request
     * @param $userSalt
     * @param \YourBooks\BookBundle\Entity\Book $book
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @internal param $bookId
     * @return mixed
     *
     * @ParamConverter("book", options={"mapping": {"bookId": "id"}})
     * @Secure(roles="ROLE_AUTHOR")
     */
    public function indexAction(Request $request, $userSalt, Book $book)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($userSalt !== $user->getSalt())
            throw new AccessDeniedHttpException('Vous n\'avez pas accès à cette page !');

        return $this->render('YourBooksPaymentBundle:Paypal:index.html.twig', [
            'user' => $user,
            'book' => $book,
        ]);
    }

    public function paymentAction()
    {

    }

    public function treatmentAction(Request $request)
    {
        /**
         * @var \Symfony\Bridge\Monolog\Logger
         */
        $logger = $this->get('logger');


        $data = $request->request->all();

        $text = null;

        foreach ($data as $key => $value)
            $text .= '['.$key.']'.$value.'  /  ';

        $logger->error($text);

        if (!in_array('payment_status', $data))
            throw new BadRequestHttpException('`payment_status` must be defined');

        $payment = new Payment();
        $payment->setStatus($data['payment_status']);
        $payment->setData($data);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($payment);
        $em->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('YourBooks')
            ->setFrom('thibaud.bardin+yourbooks@gmail.com')
            ->setTo('thibaud.bardin@gmail.com')
            ->setBody($request->__toString())
        ;
        $this->get('mailer')->send($message);

        return new Response($request);
    }
}
