<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/12/2013
 * Time: 15:34
 */

namespace YourBooks\UserBundle\ConfirmMail;

use Symfony\Component\EventDispatcher\Event;
use FOS\UserBundle\Model\UserInterface;

class UserRegisterEvent extends Event
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

} 