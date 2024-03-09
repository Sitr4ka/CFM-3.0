<?php

namespace App\Controller;

use App\Entity\Churches;
use App\Entity\Incomes;
use App\Entity\Outcomes;
use App\Form\IncomeRegistrationType;
use App\Form\OutcomeRegistrationType;
use App\Repository\ChurchesRepository;
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
    public function incomeRegistration(Request $request, EntityManagerInterface $em): Response
    {
        $income = new Incomes;
        $form = $this->createForm(IncomeRegistrationType::class, $income);
        

        $form->handleRequest($request);
        $church = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) { 
            $income->setChurches($church);
            $em->persist($income);
            $em->flush();
            $this->addFlash(
                'success',
                'Registration successfully completed',
            ); 
            return $this->redirectToRoute('app_home');
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
        $form = $this->createForm(OutcomeRegistrationType::class, $outcome);

        $form->handleRequest($request);
        $church = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) { 
            $outcome->setChurches($church);
            $em->persist($outcome);
            $em->flush();
            $this->addFlash(
                'success',
                'Registration successfully completed',
            ); 
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/outcome.html.twig',[
            'slug' => 'Outcome Registration',
            'outcomes' => $form,
        ]);
    }
}
