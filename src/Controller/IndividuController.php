<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\IndividuExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IndividuExploitationRepository;
use App\Repository\MouvementExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/experimentation")
 */
class IndividuController extends AbstractController
{
    /**
     * @Route("/{idExpe}/lot/{idLot}/individu", name="individu_index")
     */
    public function index(IndividuExploitationRepository $individuExploitationRepository, LotExploitation $lot, ExperimentationExploitation $expe, MouvementExploitationRepository $mvmtRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $individus = $individuExploitationRepository->findAllByLot($lot->getIdLot());

            //GetEffectifs
            $effectifParIndi = [];
            foreach ($individus as $individu){
                $mvmts = $mvmtRepo->findEffectifByIndi($individu->getIdIndi());
                if (sizeof($mvmts)>0){
                    $effectifParIndi[$individu->getIdIndividuExploitation()] = $mvmts[0]->getNouvelEffectif();
                }
            }

            return $this->render('individu/index.html.twig', [
                'idLot' => $lot->getIdLot(),
                'individus' => $individus,
                'idExpe' => $expe->getIdExpe(),
                'effectifParIndi' => $effectifParIndi,
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
        
    }

}
