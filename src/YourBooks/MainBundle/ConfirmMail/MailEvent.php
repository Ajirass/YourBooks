<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/12/2013
 * Time: 15:34
 */

namespace YourBooks\MainBundle\ConfirmMail;

use Symfony\Component\EventDispatcher\Event;
use Application\Sonata\UserBundle\Entity\User;

class MailEvent extends Event
{
    protected $user;
    protected $message;
    protected $subject;

    public function __construct(User $user, $message, $subject)
    {
        $this->user = $user;
        $this->message = $message;
        $this->subject = $subject;
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

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

} 