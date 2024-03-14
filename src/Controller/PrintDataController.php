<?php

namespace App\Controller;

use App\Repository\ChurchesRepository;
use App\Repository\IncomesRepository;
use App\Repository\OutcomesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrintDataController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ChurchesRepository $repository, EntityManagerInterface $em): Response
    {
        $churches = $repository->findAll();
        $churche = $this->getUser();
        
        $incomes = $churche->getIncomes();
        $outgoing = $churche->getOutgoing();
        $balance = $incomes - $outgoing;
        $churche->setBalance($balance);
        $em->persist($churche);
        $em->flush();


        return $this->render('home/index.html.twig', [
            'slug' => 'Welcome to CFM',
            'churches' => $churches,
        ]);
    }

    #[Route('/home/dashboard', name: 'app_dashboard')]
    public function dashboard(OutcomesRepository $outcomesRepository, IncomesRepository $incomesRepository) : Response
    {
        $user = $this->getUser();
        $incomes = $incomesRepository->findBy([
            'churches' => $user,
        ], [
            'executedAt' => 'ASC'      
        ]);
        $outcomes = $outcomesRepository->findBy([
            'churches' => $user,
        ], ['executedAt' => 'ASC'
        ]);
        // dd($outcomes);
        return $this->render('home/dashboard.html.twig', [
            'slug' => 'Dashboard',
            'outcomes' => $outcomes,
            'incomes' => $incomes,
        ]);
    }
}