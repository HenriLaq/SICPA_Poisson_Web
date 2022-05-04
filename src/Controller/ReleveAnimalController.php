<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReleveAnimalExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReleveAnimalController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/individu/{idIndi}/releve", name="releve_indi_index")
     */
    public function index(ReleveAnimalExploitationRepository $releveAnimalExploitationRepository, IndividuExploitation $indi, LotExploitation $lot, ExperimentationExploitation $expe): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){
            $releves = $releveAnimalExploitationRepository->findRelByIndi($indi->getIdIndi());
            return $this->render('releve_animal/index.html.twig', [
                'idExpe' => $expe->getIdExpe(),
                'idLot' => $lot->getIdLot(),
                'idIndi' => $indi->getIdIndi(),
                'releves' => $releves,
            ]);
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
        
        
    }
}
