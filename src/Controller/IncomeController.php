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
                $church->updateIncomes($incomesRepository, $em, $church);                
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
            // Updating Incomes
            $church = $this->getUser();
            $church->updateIncomes($incomesRepository, $em, $church);
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
        public function editIncome(Request $request, EntityManagerInterface $em, Incomes $incomes, IncomesRepository $iR): Response
        {
            $church = $this->getUser();
            $form = $this->createForm(IncomeRegistrationType::class, $incomes);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) { 
                $em->persist($incomes);
                $em->flush();
                $church->updateIncomes($iR,$em, $church);
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