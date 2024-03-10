<?php

namespace App\Controller;

use App\Entity\Incomes;
use App\Entity\Outcomes;
use App\Form\IncomeRegistrationType;
use App\Form\OutcomeRegistrationType;
use App\Repository\ChurchesRepository;
use App\Repository\IncomesRepository;
use App\Repository\OutcomesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ChurchesRepository $repository): Response
    {
        $churches = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'slug' => 'Welcome to CFM',
            'churches' => $churches,
        ]);
    }

    #[Route('/home/income', name:'app_income')]
    public function incomeRegistration(Request $request, ChurchesRepository $repository, EntityManagerInterface $em): Response
    {
        $income = new Incomes;
        $user = $this->getUser();
        $balance = $this->getUser()->getBalance();
        $form = $this->createForm(IncomeRegistrationType::class, $income);
        
        $form->handleRequest($request);
        $church = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) { 
            $income->setChurches($church);
            $amount = $income->getAmount();
            $balance = $balance + $amount;
            $user->setBalance($balance);
            $em->persist($income);
            $em->flush();
            $this->addFlash(
                'success',
                'Registration successfully completed',
            ); 
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('home/income.html.twig',[
            'slug' => 'Income Registration',
            'incomes' => $form,
        ]);
    }

    #[Route('/home/outcome', name:'app_outcome')]
    public function outcomeRegistration(Request $request, EntityManagerInterface $em): Response
    {
        $outcome = new Outcomes;
        $user = $this->getUser();
        $balance = $this->getUser()->getBalance();
        $form = $this->createForm(OutcomeRegistrationType::class, $outcome);

        $form->handleRequest($request);
        $church = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) { 
            $outcome->setChurches($church);
            $amount = $outcome->getAmount();
            $balance = $balance - $amount;

            if ($balance >= 10000)
            {

                $user->setBalance($balance);           
                $em->persist($outcome);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Registration successfully completed',
                ); 
            } else {
                $this->addFlash(
                   'warning',
                   'You don\'t have enough solde',
                );
                return $this->redirectToRoute('app_outcome');
            }
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('home/outcome.html.twig',[
            'slug' => 'Outcome Registration',
            'outcomes' => $form,
        ]);
    }

    #[Route('/home/dashboard', name: 'app_dashboard')]
    public function dashboard(OutcomesRepository $outcomesRepository, IncomesRepository $incomesRepository) : Response
    {
        $user = $this->getUser();
        $incomes = $incomesRepository->findBy([
            'churches' => $user,
        ]);
        $outcomes = $outcomesRepository->findBy([
            'churches' => $user,
        ]);
        // dd($outcomes);
        return $this->render('home/dashboard.html.twig', [
            'slug' => 'Dashboard',
            'outcomes' => $outcomes,
            'incomes' => $incomes,
        ]);
    }
}