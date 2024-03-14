<?php

namespace App\Controller;

use App\Entity\Incomes;
use App\Form\IncomeRegistrationType;
use App\Repository\IncomesRepository;
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
                $em->persist($income);
                $em->flush();

                // UPDATING TOTAL INCOMES
                $total = 0; 
                $incomes = $incomesRepository->findBy([  
                    'churches' => $church,
                ]);
                foreach ($incomes as $value) {
                    $total += $value->getAmount();
                }
                $totalIncomes = $church->setIncomes($total);
                $em->persist($church);
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

    /* 
     * REMOVE
     */
        #[Route('/home/income/delete/{id}', name: 'app_deleteIncome')]
        public function deleteIncome(EntityManagerInterface $em, Incomes $incomes, IncomesRepository $incomesRepository): Response
        {
            $em->remove($incomes);
            $em->flush();
                            // UPDATING TOTAL INCOMES
                            $total = 0; 
                            $church = $this->getUser();
                            $incomes = $incomesRepository->findBy([  
                                'churches' => $church,
                            ]);
                            foreach ($incomes as $value) {
                                $total += $value->getAmount();
                            }
                            $totalIncomes = $church->setIncomes($total);
                            $em->persist($church);
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
}