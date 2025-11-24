<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de la page d'accueil.
 *
 * Gère l'affichage des formations récentes et des pages statiques.
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Constructeur.
     *
     * @param FormationRepository $repository Repository pour accéder aux formations.
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }   
    
    /*
     * Affiche la page d'accueil avec les 2 dernières formations.
     *
     * @Route("/", name="accueil")
     * @return Response La réponse HTTP contenant la page d'accueil.
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]); 
    }
    
    /*
     * Affiche la page des conditions générales d'utilisation.
     *
     * @Route("/cgu", name="cgu")
     * @return Response La réponse HTTP contenant la page CGU.
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig"); 
    }
}
