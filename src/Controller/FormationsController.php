<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    
    /*
     * Chemin du template des formations.
     */
    private const PAGEFORMATIONS = "pages/formations.html.twig";
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /*
     * Le Constructeur
     * 
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /*
     * Affiche toutes les formations et catégories.
     *
     * @Route("/formations", name="formations")
     * @return Response La réponse HTTP contenant la page des formations.
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /*
     * Trie les formations selon un champ et un ordre donnés.
     *
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param string $champ Le champ à trier
     * @param string $ordre L'ordre de tri (ASC ou DESC)
     * @param string $table Table optionnelle pour le tri
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     

    /*
     * Recherche des formations contenant une valeur dans un champ donné.
     *
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param string $champ Le champ sur lequel rechercher
     * @param Request $request La requête HTTP
     * @param string $table Table optionnelle
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    /*
     * Affiche une formation spécifique par son identifiant.
     *
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param int $id Identifiant de la formation
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
}
