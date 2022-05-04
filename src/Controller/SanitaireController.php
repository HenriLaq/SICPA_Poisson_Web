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
        $user = $this->getUser();
        $sanitaires = [];
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){
            $sanitaires = $SanitaireExploitationRepository->findSanByIndi($indi->getIdIndi());
            return $this->render('sanitaire/index.html.twig', [
                'idExpe' => $expe->getIdExpe(),
                'idLot' => $lot->getIdLot(),
                'idIndi' => $indi->getIdIndi(),
                'sanitaires' => $sanitaires,
            ]);
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
        
        
    }
}
