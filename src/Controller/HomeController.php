<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'slug' => 'Welcome to CFM'
        ]);
    }

    #[Route('/home/dashboard', name:'app_dashboard')]
    public function transaction(): Response
    {
        return $this->render('home/transaction.html.twig',[
            'slug' => 'Dashboard',
        ]);
    }
}
