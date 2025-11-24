<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des playlists.
 *
 * Gère l'affichage, le tri et la recherche des playlists et leurs catégories/formations.
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {
    
    
    /*
     * chemin du template des playlits
     */
    private const PAGEPLAYLISTS = "pages/playlists.html.twig";
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
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
     * Constructeur.
     *
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRepository
     */
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Affiche toutes les playlists avec les catégories.
     *
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }

    /*
     * Trie les playlists selon un champ donné.
     *
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
     * @param string $champ Champ de tri
     * @param string $ordre Ordre de tri (ASC ou DESC)
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAll();
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          

    /*
     * Recherche des playlists contenant une valeur dans un champ donné.
     *
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontain")
     * @param string $champ Champ sur lequel rechercher
     * @param Request $request Requête HTTP
     * @param string $table Table optionnelle
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    /*
     * Affiche une playlist spécifique avec ses catégories et formations.
     *
     * @Route("/playlists/playlist/{id}", name="playlists.showone")
     * @param int $id 
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }       
    
}
