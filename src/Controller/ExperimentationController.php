<?php

namespace App\Controller;

use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExperimentationExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExperimentationController extends AbstractController
{
    /**
     * @Route("/", name="experimentation_index")
     */
    public function index(ExperimentationExploitationRepository $experimentationExploitationRepository, LotExploitationRepository $lotRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $experimentations = $experimentationExploitationRepository->findByUser(
            $user->getIdUtili(),
            $user->getRoles()[0],
            $user->getIdEquipe(),
            $user->getFinEstMembre()
        );

        $lotsParExpe = [];
        foreach($experimentations as $experimentation){
            $lotsParExpe[$experimentation->getIdExpe()] = count($lotRepo->findCountByExpe($experimentation->getIdExpe()));
        }

        return $this->render('experimentation/index.html.twig', [
            'experimentations' => $experimentations,
            'lotsParExpe' => $lotsParExpe,
        ]);
    }

}
