<?php

namespace App\Controller;

use App\Entity\LotExploitation;
use App\Entity\BassinExploitation;
use App\Entity\ExperimentationExploitation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AlimentationEauExploitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlimentationEauController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/bassin/{idBassin}/source/{idAlimEau}", name="alimentation_eau_index")
     */
    public function index(AlimentationEauExploitationRepository $AlimentationEauExploitationRepository, ExperimentationExploitation $expe, LotExploitation $lot, BassinExploitation $bassin, $idAlimEau): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $sources = $AlimentationEauExploitationRepository->findSourceByAlim($idAlimEau);
            return $this->render('alimentation_eau/index.html.twig', [
                'idLot' => $lot->getIdLot(),
                'sources' => $sources,
                'idExpe' => $expe->getIdExpe(),
                'idBassin' => $bassin->getIdBassin()
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }


    }
}
