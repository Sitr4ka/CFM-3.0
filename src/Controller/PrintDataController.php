<?php

namespace App\Controller;

use App\Form\SearchDataType;
use App\Repository\ChurchesRepository;
use App\Repository\IncomesRepository;
use App\Repository\OutcomesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function PHPSTORM_META\type;

class PrintDataController extends AbstractController
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

    #[Route('/home/search', name: 'app_search')]
    public function searchIncome(
        Request $request, 
        IncomesRepository $incomesRepository,
        OutcomesRepository $outcomesRepository) : Response 
    {
        $searchIncomeForm = $this->createForm(SearchDataType::class);
        $searchIncomeForm->handleRequest($request);
        $types = null;
        
        if ($searchIncomeForm->isSubmitted() && $searchIncomeForm->isValid()) { 
            $input = $searchIncomeForm->getData();
            //Verification des inputs
            $church = $this->getUser();
            if ($input['type'] == 'Incomes') 
            {
                $incomes = $incomesRepository->findByMotif($input, $church);
                $types = $incomes;

            } else {
                $outcomes = $outcomesRepository->findByMotif($input, $church);
                $types= $outcomes;
            }         
        }

        return $this->render('home/search.html.twig',[
            'slug' => 'Chart',
            'search_Form' => $searchIncomeForm->createView(),
            'types' => $types,
        ]);    
    }
}