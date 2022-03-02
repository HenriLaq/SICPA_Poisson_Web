<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\ReleveAnimalExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot", name="lot_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, IndividuExploitationRepository $indiRepo, ReleveAnimalExploitationRepository $relAniRepo, ExperimentationExploitation $expe): Response
    {
        $lots = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        /* Courbe des releves 
        $indis = [];
        $releveParIndi = [];
        $releves = [];
        $lotR = [];
        foreach($lots as $lot) {
            array_push($indis, $indiRepo->findAllByLot($lot->getIdLot()));
            foreach ($indis as $indi){
                $c = 0;
                foreach ($indi as $i){
                    array_push($releveParIndi, $relAniRepo->findRelByIndi($i->getIdIndi()));
                    $c++;
                }
            }
        }
        foreach($releveParIndi as $releve){
            foreach ($releve as $r){
                array_push($releves, $r);
            }
        }
        //dd($releves);
        */
        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe(),
        ]);
    }
}
