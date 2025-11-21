<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author Tristan
 */
class PlaylistRepositoryTest extends KernelTestCase {
    
    
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    public function newPlaylist(): Playlist{
        $playlist = (new Playlist())
                ->setName("Playlsit de test");
        return $playlist;
    }
    
    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemovePlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppression");        
    }
    
    public function testFindAllOrderByName(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $p1 = (new Playlist())->setName("A");
        $p2 = (new Playlist())->setName("Z");
        $repository->add($p1);
        $repository->add($p2);
        $result = $repository->findAllOrderByName('ASC');

        $this->assertEquals(
            "A",
            $result[0]->getName(),
            "L'ordre alphabétique n'est pas respecté"
        );
        
        $this->assertEquals(
            "Z",
            $result[$nbPlaylists + 1]->getName(),
            "L'ordre alphabétique n'est pas respecté"
        );
    }
    
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();

        $repository->add($playlist);

        $result = $repository->findByContainValue("name", "Playlsit de test");
        $this->assertCount(1, $result, 
                "Erreur : le filtre 'Playlsit de test' ne retourne pas le bon nombre de résultats.");
        $this->assertEquals("Playlsit de test", $result[0]->getName());
    }
    
    public function testFindAllOrderByNbFormations(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $resultDesc = $repository->findAllOrderByNbFormations("DESC");
        $resultAsc = $repository->findAllOrderByNbFormations("ASC");
        
        $this->assertGreaterThan($resultDesc[$nbPlaylists - 1]->getNbFormations(), 
                $resultDesc[0]->getNbFormations());
        $this->assertGreaterThan($resultAsc[0]->getNbFormations(), 
                $resultAsc[$nbPlaylists - 1]->getNbFormations());
    }
}
