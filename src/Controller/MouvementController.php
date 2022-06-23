<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MouvementExploitationRepository;
use App\Repository\ReleveAnimalExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{

    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/mouvements", name="mouvement_index")
     */
    public function index(MouvementExploitationRepository $mouvementExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe, ReleveAnimalExploitationRepository $relAniRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $mouvements = $mouvementExploitationRepository->findHistoByLot($lot->getIdLot());

            return $this->render('mouvement/index.html.twig', [
                'idExpe' => $expe->getIdExpe(),
                'lot' => $lot,
                'mouvements' => $mouvements,
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }

        
    }
}
