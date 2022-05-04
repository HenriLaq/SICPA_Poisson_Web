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
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, CourbePrevisionnelleRepository $courbeRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $lotsExploitation = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        $courbes = $this->getCourbesBDD($lotsExploitation, $courbeRepo);
        return $this->render('lot/index.html.twig', [
            'lots' => $lotsExploitation,
            'idExpe' => $expe->getIdExpe(),
            'courbes' => $courbes,
        ]);
    }

    private function getCourbesBDD($lotsExploitation, $courbeRepo){
        $courbesByLot = [];
        $courbes = [];
        foreach($lotsExploitation as $lot) {
            array_push($courbesByLot, $courbeRepo->findCourbeByLot($lot->getIdLot()));
        }
        foreach($courbesByLot as $courbeByLot){
            foreach($courbeByLot as $courbe){
                array_push($courbes, $courbe);
            }
        }
        return $courbes;
    }
}
