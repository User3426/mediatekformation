<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur d'administration des playlists.
 *
 * Gère l'affichage, le tri, la recherche et la modification des playlists.
 *
 * @author Tristan
 */
class AdminCategorieController extends AbstractController {
    
    /*
     * chemin du template admincategories
     */
    private const PAGEADMINCATEGORIES = "admin/admin.categories.html.twig";
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /*
     * @param CategorieRepository $categorieRepository
     */
    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository= $categorieRepository;
    }
    
    /*
     * Affiche toutes les catégories.
     *
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response{
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEADMINCATEGORIES, [
            'categories' => $categories
        ]);
    }
    
    /*
     * Supprime une catégorie si elle n'a pas de formations.
     *
     * @Route("/admin/categorie/suppr/{id}", name="admin.categorie.suppr")
     * @param int $id
     * @return Response
     */
    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response{
        $categorie = $this->categorieRepository->find($id);
        
        // Vérifie si la catégorie contient des formations
        if (!$categorie->getFormations()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer cette catégorie : elle est rattachée à des formations.');
            return $this->redirectToRoute('admin.categories');
        }
        
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
    
    /*
     * Ajoute une nouvelle catégorie.
     *
     * @Route("/admin/categorie/ajout", name="admin.categorie.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categorie/ajout', name: 'admin.categorie.ajout')]
    public function ajout(Request $request): Response{
        $nomCategorie = $request->get("nom");
        
        // Vérifie si une catégorie avec ce nom existe déjà
        if ($this->categorieRepository->findOneBy(['name' => $nomCategorie])) {
            $this->addFlash('error', "La catégorie '$nomCategorie' existe déjà.");
            return $this->redirectToRoute('admin.categories');
        }
        
        $categorie = new Categorie();
        $categorie->setName($nomCategorie);
        $this->categorieRepository->add($categorie);
        return $this->redirectToRoute('admin.categories');
    }
}
