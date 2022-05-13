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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LotController extends AbstractController
{
    /**
     * @Route("/experimentation/{idExpe}/lot", name="lot_index")
     */
    public function index(LotExploitationRepository $lotExploitationRepository, ExperimentationExploitation $expe, CourbePrevisionnelleRepository $courbeRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if ((($user->getRoles()[0] == "ROLE_SUPER_ADMIN") 
        OR ($user->getRoles()[0] == "ROLE_ADMIN_UNITE" AND $user->getIdUnite() == $expe->getIdUnite())
        OR ($user->getIdUtili() == $expe->getIdEstRespTechn()) 
        OR ($user->getIdUtili() == $expe->getIdUtili()) )
        AND $user->getFinEstMembre() == null){

            $lotsExploitation = $lotExploitationRepository->findAllByExpe($expe->getIdExpe());
            $courbes = $this->getCourbesBDD($lotsExploitation, $courbeRepo);
            return $this->render('lot/index.html.twig', [
                'lots' => $lotsExploitation,
                'idExpe' => $expe->getIdExpe(),
                'courbes' => $courbes,
            ]);
            
        }else{
            return $this->render('security/interdit.html.twig', []);
        }
    }

    /**
     * @Route("/experimentation/{idExpe}/lot/{idLot}/form", name="lot_form")
     */
    public function form(Request $request, ExperimentationExploitation $expe, LotExploitationRepository $lotExploitationRepository, LotExploitation  $lotExploitation, IndividuExploitationRepository $indiRepo, ReleveAnimalExploitationRepository $relRepo): Response
    {
        $lotsExploitation = $lotExploitationRepository->find($lotExploitation->getIdLotExploitation());
        $indis = $indiRepo->findByLotForm($lotsExploitation->getIdLot());
        $rels = [];
        $relsSepares = [];
        $idParDate = [];
        foreach($indis as $indi){
            $rels = $relRepo->findByIndiForm($indi->getIdIndi());
            foreach($rels as $rel){
                array_push($relsSepares, $rel);
                $idParDate[($rel->getDateRelAni())->format('d-m-Y')] = $rel->getIdRelAni();
            }
        }

        $form = $this->createFormBuilder()
            ->add('premier', ChoiceType::class, ['label' => 'Premier relevé', 'choices' => ['Date' => $idParDate],])
            ->add('dernier', ChoiceType::class, ['label' => 'Dernier relevé', 'choices' => ['Date' => $idParDate]])
            ->add('valider', SubmitType::class, ['label' => 'Valider'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $relsBons = [];
            foreach($relsSepares as $rel){
                if ($rel->getDateRelAni() >= $relRepo->findByRelId($data['premier'])[0]->getDateRelAni() AND $rel->getDateRelAni() <= $relRepo->findByRelId($data['dernier'])[0]->getDateRelAni()){
                    array_push($relsBons, $rel);
                }
            }
            dd($relsBons);
            //return $this->redirectToRoute('lot_index', array('idExpe' => $expe->getIdExpe()));
        }

        return $this->render('lot/bilanZootechnique.html.twig', [
            'idExpe' => $expe->getIdExpe(),
            'lots' => $lotsExploitation,
            'form' => $form->createView(),
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
