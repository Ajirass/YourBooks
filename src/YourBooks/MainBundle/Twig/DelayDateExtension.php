<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 27/03/2014
 * Time: 00:12
 */

namespace YourBooks\MainBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class DelayDateExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('delayDateOut', array($this, 'delayDateFunction')),
        );
    }

    public function delayDateFunction($dateCompare)
    {
        $delayDateOut = $dateCompare->modify('+2 day');
        return $delayDateOut;
    }

    public function getName()
    {
        return 'delayDateOut';
    }
} 