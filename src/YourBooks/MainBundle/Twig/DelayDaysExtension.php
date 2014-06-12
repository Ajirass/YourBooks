<?php
/**
 * Created by PhpStorm.
 * User: robingodart
 * Date: 25/03/2014
 * Time: 10:56
 */

namespace YourBooks\MainBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class DelayDaysExtension extends \Twig_Extension
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('delayDaysDiff', array($this, 'delayDaysFunction')),
        );
    }

    public function delayDaysFunction($daysCompare)
    {
        $dateNow = new \DateTime();
        $daysCompare->modify('-18 day');
        $interval = $dateNow->diff($daysCompare, null);
        $day = $interval->format('%d');
        $delayOut = 18 - $day;
        return $delayOut;
    }

    public function getName()
    {
        return 'delayDaysOut';
    }
} 