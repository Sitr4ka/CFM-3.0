<?php

namespace App\Controller;

use App\Form\SearchDataType;
use App\Repository\ChurchesRepository;
use App\Repository\IncomesRepository;
use App\Repository\OutcomesRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

use function PHPSTORM_META\type;

class PrintDataController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ChurchesRepository $repository): Response
    {
        if ( !($user = $this->getUser())) {
            return $this->redirectToRoute('app_login');
        }
        $churches = $repository->findAll();  
        return $this->render('printData/index.html.twig', [
            'user' => $user->getDesign(),
            'slug' => 'Welcome to CFM',
            'churches' => $churches,
        ]);
    }

    #[Route('/home/dashboard', name: 'app_dashboard')]
    public function dashboard(
        OutcomesRepository $outcomesRepository, 
        IncomesRepository $incomesRepository,
        ) : Response
    {
        $user = $this->getUser();
                $incomes = $incomesRepository->findBy([
                                'churches' => $user,
                            ], [
                                'executedAt' => 'ASC'      
                            ]);
                $outcomes = $outcomesRepository->findBy([
                                'churches' => $user,
                            ], [
                                'executedAt' => 'ASC'
                            ]);

        /* Chart Data settings */
        $previousMonth = new \DateTime('first day of last month');
        $actualMonth = new \DateTime('last day of this month');
        $startDate = $previousMonth->format('Y-m-d');
        $endDate = $actualMonth->format('Y-m-d');
                $incomesMonth = $incomesRepository->findByLastMonth($startDate, $endDate, $user);
                $dataIn = [];
                $date = null;
                foreach ($incomesMonth as $income) {
                    $week = $income->getExecutedAt()->format('W-Y');
                    // Divisez la chaîne de semaine en numéro de semaine et année
                    list($weekNumber, $year) = explode('-', $week);
                    // Obtenez la date du premier jour de la semaine à partir du numéro de semaine et de l'année
                    $firstDayOfWeek = strtotime($year . 'W' . $weekNumber);  
                    // Formattez la date pour obtenir le lundi de cette semaine
                    $dateLundi = date('d-M', $firstDayOfWeek);    
                                          
                    if (!isset($dataIn[$dateLundi])) {
                        $dataIn[$dateLundi] = 0;
                        $date[] = $dateLundi;
                    }
                    $dataIn[$dateLundi] += $income->getAmount();
                }
                $dataOut = [];
                foreach ($outcomes as $outcome) {
                    $week2 = $outcome->getExecutedAt()->format('W-Y');
                    
                    if (!isset($dataOut[$week2])) {
                        $dataOut[$week2] = 0;
                    }
                    $dataOut[$week2] += $outcome->getAmount();
                }
        return $this->render('printData/dashboard.html.twig', [
        'slug' => 'Dashboard',
        'outcomes' => $outcomes,
        'incomes' => $incomes,
        'date' => json_encode($date),
        'dataIn' => json_encode($dataIn),
        'dataOut' => json_encode($dataOut),
        'user' => $user->getDesign(),
        ]);
    }

    #[Route('/home/search', name: 'app_search')]
    public function searchIncome(
        Request $request, 
        IncomesRepository $incomesRepository,
        OutcomesRepository $outcomesRepository,
        SessionInterface $session) : Response 
    {
        $searchIncomeForm = $this->createForm(SearchDataType::class);
        $searchIncomeForm->handleRequest($request);
        $incomes = null;
        $outcomes =null;
        $totalIncomes = 0;
        $totalOutcomes = 0;
        $church = $this->getUser();

        if ($searchIncomeForm->isSubmitted() && $searchIncomeForm->isValid()) { 
            $input = $searchIncomeForm->getData();
            //Verification des inputs
            $outcomes = $outcomesRepository->findByMotif($input, $church);
            $incomes = $incomesRepository->findByMotif($input, $church);
            
            if ($input['type'] == 'Incomes') 
            {
                foreach ($incomes as $income) {
                    $totalIncomes += $income->getAmount();
                }
                $outcomes = null;
            } elseif ($input['type'] == 'Outcomes') {
                foreach ($outcomes as $outcome) {
                    $totalOutcomes += $outcome->getAmount();
                }
                $incomes = null;
            } else {
                foreach ($outcomes as $outcome) {
                    $totalOutcomes += $outcome->getAmount();
                }
                foreach ($incomes as $income) {
                    $totalIncomes += $income->getAmount();
                }
            }
            $session->set('search_results', [
                'incomes' => $incomes,
                'outcomes' => $outcomes,
                'totalIncomes' => $totalIncomes,
                'totalOutcomes' => $totalOutcomes,
                'startDate' => $input['startDate'],
                'endDate' => $input['endDate'],
            ]);
        }
    
        return $this->render('printData/search.html.twig',[
            'slug' => 'Search',
            'search_Form' => $searchIncomeForm->createView(),
            'incomes' => $incomes,
            'outcomes' => $outcomes,
            'totalOutcomes' => $totalOutcomes,
            'totalIncomes' => $totalIncomes,
            'user' => $church->getDesign(),
        ]);   
    }
    
    #[Route('/home/search/printData/Download', name: 'app_search_finances_report')]
    
    public function exportPdf(SessionInterface $session) : Response 
    {
        //Setting up pdf options
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        $domPdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => ([ 
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
            )
        ]);
        $domPdf->setHttpContext($context);
//** */
        $searchResults=$session->get('search_results', [
            'incomes' => null,
            'outcomes' => null,
            'totalIncomes' => null,
            'totalOutcomes' => null,
            'startDate' => null,
            'endDate' => null,
        ]);

        $incomes = $searchResults['incomes'];
        $outcomes = $searchResults['outcomes'];
        $totalOutcomes = $searchResults['totalOutcomes'];
        $totalIncomes = $searchResults['totalIncomes'];
        $startDate = $searchResults['startDate'];
        $endDate = $searchResults['endDate'];

        $html = $this->renderView('printData/pdfData.html.twig', [
            'design' => $this->getUser()->getDesign(),
            'slug' => 'View Pdf',
            'incomes' => $incomes,
            'outcomes' => $outcomes,
            'totalOutcomes' => $totalOutcomes,
            'totalIncomes' => $totalIncomes,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
/** */
        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4', 'portrait');
        $domPdf->render();
        $fichiers = $this->getUser()->getDesign() .'.pdf';
        $domPdf->stream($fichiers, [
            'Attachment' => true,
        ]);

        return new Response();
    }
}