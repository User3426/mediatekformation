<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author Tristan
 */
class AdminPlaylistsController extends AbstractController {
    
    /*
     * chemin du template adminplaylists
     */
    private const PAGEADMINPLAYLISTS = "admin/admin.playlists.html.twig";
    
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
     * Constructeur
     * 
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * Affiche toutes les playlists
     * 
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEADMINPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /*
     * Trie les playlists selon un champ et un ordre donnés.
     *
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param string $champ
     * @param string $ordre
     * @return Response
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
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
        return $this->render(self::PAGEADMINPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          

    /*
     * Recherche des playlists contenant une valeur dans un champ donné.
     *
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param string $champ
     * @param Request $request
     * @param string $table
     * @return Response
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEADMINPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    /*
     * Supprime une playlist.
     *
     * @Route("/admin/playlist/suppr/{id}", name="admin.playlist.suppr")
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlist/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id): Response{
        $playlist = $this->playlistRepository->find($id);
        
        // Vérifie si la playlist contient des formations
        if (!$playlist->getFormations()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer cette playlist : elle contient encore des formations.');
            return $this->redirectToRoute('admin.playlists');
        }
        
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }
    
    /*
     * Modifie une playlist existante.
     *
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView(),
            'playlistformations' => $playlist->getFormations()
        ]);
    }
    
    /*
     * Ajoute une nouvelle playlist.
     *
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/ajout}', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
}
