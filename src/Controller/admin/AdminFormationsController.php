<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur d'administration des formations.
 *
 * Gère l'affichage, le tri, la recherche, la modification et l'ajout des formations.
 *
 * @author Tristan
 */
class AdminFormationsController extends AbstractController{
    
    
    /*
     * chemin du template pour adminformations
     */
    private const PAGEADMINFORMATIONS = "admin/admin.formations.html.twig";
    
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
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    #[Route('/admin', name: 'admin.formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEADMINFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /*
     * Trie les formations selon un champ et un ordre donnés.
     *
     * @Route("admin/formations/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param string $champ
     * @param string $ordre
     * @param string $table
     * @return Response
     */
    #[Route('admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEADMINFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     

    /*
     * Recherche des formations contenant une valeur dans un champ donné.
     *
     * @Route("admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    #[Route('admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEADMINFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    /*
     * Supprime une formation.
     *
     * @Route("/admin/formation/suppr/{id}", name="admin.formation.suppr")
     * @param int $id
     * @return Respons
     */
    #[Route('/admin/formation/suppr/{id}', name: 'admin.formation.suppr')]
    public function suppr(int $id): Response{
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }
    
    /*
     * Modifie une formation existante.
     *
     * @Route("/admin/formation/edit/{id}", name="admin.formation.edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/formation/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response{
        $formation = $this->formationRepository->find($id);
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    /*
     * Ajoute une nouvelle formation.
     *
     * @Route("/admin/formation/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/formation/ajout', name: 'admin.formation.ajout')]
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render("admin/admin.formation.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
}
