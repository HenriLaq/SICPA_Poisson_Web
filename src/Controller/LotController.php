<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BassinExploitationRepository;
use App\Repository\CourbePrevisionnelleRepository;
use App\Repository\IndividuExploitationRepository;
use App\Repository\MouvementExploitationRepository;
use App\Repository\ReleveAnimalExploitationRepository;
use App\Repository\AlimentationEauExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot", name="lot_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, IndividuExploitationRepository $indiRepo, 
    ReleveAnimalExploitationRepository $relAniRepo, BassinExploitationRepository $bassinRepo,
    AlimentationEauExploitationRepository $alimRepo, ExperimentationExploitation $expe,
    MouvementExploitationRepository $mvmtRepo, CourbePrevisionnelleRepository $courbeRepo): Response
    {
        $lots = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        //GetCourbeBDD
        $courbesByLot = [];
        $courbes = [];
        foreach($lots as $lot) {
            array_push($courbesByLot, $courbeRepo->findCourbeByLot($lot->getIdLot()));
        }
        
        foreach($courbesByLot as $courbeByLot){
            foreach($courbeByLot as $courbe){
                array_push($courbes, $courbe);
            }
        }


        //dd($courbes);

        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe(),
            'courbes' => $courbes,
        ]);
    }
}
