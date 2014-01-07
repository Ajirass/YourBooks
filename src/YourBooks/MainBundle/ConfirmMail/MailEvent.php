<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/12/2013
 * Time: 15:34
 */

namespace YourBooks\MainBundle\ConfirmMail;

use Symfony\Component\EventDispatcher\Event;
use FOS\UserBundle\Model\UserInterface;

class MailEvent extends Event
{
    protected $user;
    protected $message;

    public function __construct(UserInterface $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

} 