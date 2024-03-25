<?php

namespace App\Controller;

use App\Entity\Incomes;
use App\Form\IncomeRegistrationType;
use App\Repository\IncomesRepository;
use App\Repository\OutcomesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IncomeController extends AbstractController
{
    /* 
     * CREATE
     */
        #[Route('/home/income', name:'app_income')]
        public function incomeRegistration(Request $request, IncomesRepository $incomesRepository, EntityManagerInterface $em): Response
        {
            $income = new Incomes;
            $church = $this->getUser();
            
            $form = $this->createForm(IncomeRegistrationType::class, $income);         
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) { 
                $income->setChurches($church);
                $income->setMotif(ucwords($income->getMotif()));
                $em->persist($income);
                $em->flush();

                // UPDATING TOTAL INCOMES
                $church->updateIncomes($incomesRepository, $em, $church);                
                $this->addFlash(
                    'success',
                    'Registration successfully completed',
                ); 
                return $this->redirectToRoute('app_dashboard');
            }
            return $this->render('home/income/registration.html.twig',[
                'user' => $church->getDesign(),
                'slug' => 'Income Registration',
                'incomes' => $form,
            ]);
        }

    /* 
     * UPDATE
     */
        #[Route('/home/income/edit/{id}', name: 'app_editIncome')]
        public function editIncome(Request $request, EntityManagerInterface $em, Incomes $income, IncomesRepository $iR): Response
        {
            $church = $this->getUser();
            $outgoing = $church->getOutgoing();
            $incomes = $church->getIncomes();
            $lastAmount = $income->getAmount();

            $form = $this->createForm(IncomeRegistrationType::class, $income);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $amount = $income->getAmount();
                $diff = $amount - $lastAmount;
                $incomes = $incomes + $diff;
                $valid = ($incomes - $outgoing) >= 10000;         
                if (!$valid) {
                    $this->addFlash(
                        'warning',
                        'Please verify your solde',
                    );  
                } else { 
                    $income->setMotif(ucwords($income->getMotif()));
                    $em->persist($income);
                    $em->flush();
                    $church->updateIncomes($iR,$em, $church);
                    $this->addFlash(
                        'success',
                        'Modification successfully completed',
                ); 
                return $this->redirectToRoute('app_dashboard');
                }
            }
            return $this->render('home/income/edit.html.twig',[
                'user' => $church->getDesign(),
                'incomes' =>  $form->createView(),
                'slug' => "Income Modifications"
            ]);
        }       

    /* 
     * REMOVE
     */
        #[Route('/home/income/delete/{id}', name: 'app_deleteIncome')]
        public function deleteIncome(
            EntityManagerInterface $em, 
            Incomes $income, 
            IncomesRepository $incomesRepository,
            OutcomesRepository $outcomesRepository,): Response
        {   
            $amount = $income->getAmount();
            $church = $this->getUser();
            $incomes = $church->getIncomes();
            $outgoing = $church->getOutgoing();

        /*Conditions verification */
            $totalOutcomes = 0;
            $outcomes = $outcomesRepository->findBy([
                'churches' => $church,
            ], [
                'executedAt' => 'ASC'
            ]);

            foreach ($outcomes as $outcome) {
                $totalOutcomes += $outcome->getAmount();
            }

            $nextIncomes = $incomes - $amount;
            $diff = $nextIncomes - $outgoing >= 10000;
            $valid = $diff >= 10000 || $totalOutcomes == 0;
            if (!$valid) { 
                $this->addFlash(
                    'warning',
                    'Please verify your solde',
                );  
            } else { 
                $em->remove($income);
                $em->flush();
                // Updating Incomes
                $church = $this->getUser();
                $church->updateIncomes($incomesRepository, $em, $church);
                $this->addFlash(
                    'success',
                    'Deletion successfully completed',
            );
            } 
            return $this->redirectToRoute('app_dashboard');
        }   

}