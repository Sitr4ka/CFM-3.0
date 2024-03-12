<?php

namespace App\Controller;

use App\Entity\Incomes;
use App\Entity\Outcomes;
use App\Form\IncomeRegistrationType;
use App\Form\OutcomeRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /* 
     * CREATE
     */
        #[Route('/home/income', name:'app_income')]
        public function incomeRegistration(Request $request, EntityManagerInterface $em): Response
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
            return $this->render('home/income/registration.html.twig',[
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
            return $this->render('home/outcome/registration.html.twig',[
                'slug' => 'Outcome Registration',
                'outcomes' => $form,
            ]);
        }
    /* 
     * REMOVE
     */
    #[Route('/home/income/delete/{id}', name: 'app_deleteIncome')]
    public function deleteIncome(EntityManagerInterface $em, Incomes $incomes): Response
    {
        if (!$incomes) {
            $this->addFlash(
                'success',
                'Income NOT FOUND',
            ); 
            return $this->redirectToRoute('app_dashboard');
        }
        $em->remove($incomes);
        $em->flush();
        $this->addFlash(
            'success',
            'Deletion successfully completed',
        ); 
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/home/outcome/delete/{id}', name: 'app_deleteOutcome')]
    public function deleteOutcome(EntityManagerInterface $em, Outcomes $outcomes): Response
    {
        if (!$outcomes) {
            $this->addFlash(
                'success',
                'Income NOT FOUND',
            ); 
            return $this->redirectToRoute('app_dashboard');
        }
        $em->remove($outcomes);
        $em->flush();
        $this->addFlash(
            'success',
            'Deletion successfully completed',
        ); 
        return $this->redirectToRoute('app_dashboard');
    }
    

    /* 
     * UPDATE
     */
        #[Route('/home/income/edit/{id}', name: 'app_editIncome')]
        public function editIncome(Request $request, EntityManagerInterface $em, Incomes $incomes): Response
        {
            $form = $this->createForm(IncomeRegistrationType::class, $incomes);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) { 
                $em->persist($incomes);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Modification successfully completed',
                ); 
                return $this->redirectToRoute('app_dashboard');
            }
            return $this->render('home/income/edit.html.twig',[
                'incomes' =>  $form->createView(),
                'slug' => "Income Modifications"
            ]);
        }
        
        #[Route('/home/outcome/edit/{id}', name: 'app_editOutcome')]
        public function editOutcome(Request $request, EntityManagerInterface $em, Outcomes $outcomes): Response
        {
            $form = $this->createForm(OutcomeRegistrationType::class, $outcomes);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) { 
                $em->persist($outcomes);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Modification successfully completed',
                ); 
                return $this->redirectToRoute('app_dashboard');
            }
            return $this->render('home/outcome/edit.html.twig',[
                'outcomes' =>  $form->createView(),
                'slug' => "Income Modifications"
            ]);
        }
        
}