<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CfmMethodsController extends AbstractController
{
    public function getWeek($date)
    {
        $week = date_format($date, 'w');
        return $week;
    }
}
