<?php

namespace App\Controller;

use App\Entity\Outcomes;
use App\Form\OutcomeRegistrationType;
use App\Repository\OutcomesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OutcomeController extends AbstractController
{
    /* 
     * CREATE
     */
        #[Route('/home/outcome', name:'app_outcome')]
        public function outcomeRegistration(Request $request, OutcomesRepository $outcomesRepository, EntityManagerInterface $em): Response
        {
            $outcome = new Outcomes;
            $church = $this->getUser();
            
            $form = $this->createForm(OutcomeRegistrationType::class, $outcome);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) { 
                $outcome->setChurches($church);         
                $em->persist($outcome);
                $em->flush();
                
                // UPDATING TOTAL OUTCOME
                $total = 0;
                $outcomes = $outcomesRepository->findBy([ 
                    'churches' => $church,
                ]);
                foreach ($outcomes as $value) {
                    $total += $value->getAmount();
                }
                $totalOutcomes = $church->setOutgoing($total);
                $em->persist($church);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Registration successfully completed',
                ); 
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
        #[Route('/home/outcome/delete/{id}', name: 'app_deleteOutcome')]
        public function deleteOutcome(EntityManagerInterface $em, Outcomes $outcomes, OutcomesRepository $outcomesRepository): Response
        {
            $em->remove($outcomes);
            $em->flush();
                            // UPDATING TOTAL OUTCOME
                            $total = 0;
                            $church = $this->getUser();
                            $outcomes = $outcomesRepository->findBy([ 
                                'churches' => $church,
                            ]);
                            foreach ($outcomes as $value) {
                                $total += $value->getAmount();
                            }
                            $totalOutcomes = $church->setOutgoing($total);
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