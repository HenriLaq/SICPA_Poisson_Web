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
        //query les releves
        foreach($indis as $indi){
            $rels = $relRepo->findByIndiForm($indi->getIdIndi());
            foreach($rels as $rel){
                array_push($relsSepares, $rel);
                $idParDate[($rel->getDateRelAni())->format('d-m-Y')] = $rel->getIdRelAni();
            }
        }

        //construire le form
        $form = $this->createFormBuilder()
            ->add('premier', ChoiceType::class, ['label' => 'Premier relevé', 'choices' => ['Date' => $idParDate]])
            ->add('dernier', ChoiceType::class, ['label' => 'Dernier relevé', 'choices' => ['Date' => $idParDate]])
            ->add('valider', SubmitType::class, ['label' => 'Valider'])
            ->getForm();

        //remplir les variables avec les bonnes valeurs
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $relsBons = [];

            $debut = $relRepo->findByRelId($data['premier'])[0]->getDateRelAni();
            $fin = $relRepo->findByRelId($data['dernier'])[0]->getDateRelAni();

            $pmi = $relRepo->findByRelId($data['premier'])[0]->getValeurRelAni();
            $pmf = $relRepo->findByRelId($data['dernier'])[0]->getValeurRelAni();
            $joursEcart = $debut->diff($fin)->days ;
            $gp = round(abs($pmf-$pmi)*100/($pmi*$joursEcart),3); 
            $gainind = round(abs($pmf-$pmi)/$joursEcart,3);
            $tspecroi = round(abs(log($pmf)-log($pmi))*100/$joursEcart,3);
            $icj = round(100*($pmf**(1/3)-$pmi**(1/3))/$joursEcart,3);

            $nbmort = 0;
            $pdsmort = 0;
            $nb = 0;
            $pds = 0;
            $nbfin = 0;
            $pdsfin = 0;
            dd($relRepo->findByRelId($data['premier'])[0]);
                $nb = $relRepo->findByRelId($data['premier'])[0]->getNouvelEffectif();
                $pds = $nb * $pmi;
                $nbfin = $relRepo->findByRelId($data['dernier'])[0]->getNouvelEffectif();
                $pdsfin = $nbfin * $pmf;
                //Avec les donnees de mouvements
                foreach($relsSepares as $rel){
                    if ($rel->getDateRelAni() >= $relRepo->findByRelId($data['premier'])[0]->getDateRelAni() AND $rel->getDateRelAni() <= $relRepo->findByRelId($data['dernier'])[0]->getDateRelAni()){
                        array_push($relsBons, $rel);
                        if($rel->getIdTypeMouv() == 17){
                            $nbmort += $rel->getEffectifMouvement();
                            $pdsmort += $rel->getValeurRelAni();
                        }
                    }
                }
            

            $return = $this->render('lot/bilanZootechnique.csv.twig', [
                'expe' => $expe,
                'lot' => $lotExploitation, 
                'debut' => $debut->format('d-m-Y'),
                'fin' => $fin->format('d-m-Y'),
                'pds' => $pds,
                'nb' => $nb,
                'pdsmort' => $pdsmort,
                'nbmort' => $nbmort,
                'pmi' => $pmi,
                'pmf' => $pmf,
                'pdsfin' => $pdsfin,
                'nbfin' => $nbfin,
                'gp' => $gp,
                'gainind' => $gainind,
                'tspecroi' => $tspecroi,
                'icj' => $icj,

            ]);
            $fic = 'Bilan Zootechnique '. \date("d-m-Y") . '.csv';
            $return->headers->set('Content-Disposition','attachment; filename="'.$fic.'"');
            return $return;
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
