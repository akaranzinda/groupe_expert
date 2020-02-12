<?php

namespace App\Controller;
use App\Repository\BiensRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AcheterController extends AbstractController
{
    /**
     * @Route("/acheter", name="acheter")
     */
    
    public function index(BiensRepository $biensRepository)
    {
        $villa = $biensRepository->Demeures('App:Biens', 'villa');
        $suite = $biensRepository->Demeures('App:Biens', 'suite');
        $deschamps = $biensRepository->Demeures('App:Biens', 'deschamps');

        return $this->render('acheter/index.html.twig', [
            'villas' => $villa,
            'suites' => $suite,
            'deschamps' => $deschamps,
        ]);
    }

}
