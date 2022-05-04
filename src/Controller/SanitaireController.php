<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SanitaireExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SanitaireController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/individu/{idIndi}/sanitaire", name="sanitaire_index")
     */
    public function index(SanitaireExploitationRepository $SanitaireExploitationRepository, IndividuExploitation $indi,  LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sanitaires = $SanitaireExploitationRepository->findSanByIndi($indi->getIdIndi());
        return $this->render('sanitaire/index.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'idLot' => $lot->getIdLot(),
            'idIndi' => $indi->getIdIndi(),
            'sanitaires' => $sanitaires,
        ]);
    }
}
