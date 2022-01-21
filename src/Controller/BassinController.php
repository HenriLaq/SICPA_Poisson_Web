<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\BassinExploitation;
use App\Entity\ExperimentationExploitation;
use App\Repository\LotExploitationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BassinExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BassinController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/bassin", name="bassin_index")
     */
    public function index(BassinExploitationRepository $bassinExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot): Response
    {
        $bassins = $bassinExploitationRepository->findOneById(100);

        return $this->render('bassin/index.html.twig', [
            'bassins' => $bassins,
            'idExpe' => $expe->getIdExpe()
        ]);
    }
}
