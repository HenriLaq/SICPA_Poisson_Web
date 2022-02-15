<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot", name="lot_index")
     */
    public function index(IndividuExploitationRepository $ier, LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe): Response
    {
        $lots = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());

        $indis = [];
        foreach ($lots as $lot) {

            $indis += [$ier->findIndiByLot($lot)];
            //dd($lot->getIdLot());
            //dd($ier->findAllByLot($lot->getIdLot()));
        }


        return $this->render('lot/index.html.twig', [
            'lots' => $lots,
            'idExpe' => $expe->getIdExpe()
        ]);
    }

    /**
     * @Route("/experimentation/{idExpe}/lot/zone", name="lot_zone")
     */
    public function showZone(LotExploitationRepository $lotExploitationRepository, LotExploitation $lot): Response
    {
        $lots = $lotExploitationRepository->findZoneByBassin($lot->getIdBassin());
        $plan = $lots[0]->getPlanZone();

        $data = stream_get_contents($plan);

        $response = new Response($data, 200, array('Content-Type' => 'application/pdf'));
        return $response;

        return $this->render('lot/show.html.twig', [
            'lots' => $lots,
            'plan' => $plan,
            'data' => $data
        ]);
    }
}
