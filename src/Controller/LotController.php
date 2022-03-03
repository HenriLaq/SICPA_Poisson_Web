<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BassinExploitationRepository;
use App\Repository\IndividuExploitationRepository;
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
    AlimentationEauExploitationRepository $alimRepo, ExperimentationExploitation $expe): Response
    {
        $lots = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        /* Courbe des releves */
        $indiParLot = [];
        $individus = [];
        $releveParIndi = [];

        $bassins = [];
        $releveParBassin = [];

        //Pour tous les lots
        foreach($lots as $lot) {
            array_push($indiParLot, $indiRepo->findAllByLot($lot->getIdLot()));
            array_push($bassins, ($bassinRepo->findBassinById($lot->getIdBassin()))[0]);
        }
        

        //Pour tous les grp d'indis par lots
        foreach ($indiParLot as $indi){
            //Pour tous les indis
            foreach ($indi as $i){
                array_push($releveParIndi, $relAniRepo->findRelByIndi($i->getIdIndi()));
                array_push($individus, $i);
            }
        }

        //Pour tous les bassins
        foreach($bassins as $bassin){
            array_push($releveParBassin, $alimRepo->findSourceByAlim($bassin->getIdAlimEau()));
        }
        
        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe(),
            'releveParIndi' => $releveParIndi,
            'individus' => $individus,
            'releveParBassin' => $releveParBassin,
            'bassins' => $bassins,
        ]);
    }
}
