<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author Tristan
 */
class FormationRepositoryTest extends KernelTestCase {
    
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    public function newFormation(): Formation {
        $formation = (new Formation())
                ->setTitle("Formation de Test")
                ->setVideoId("PrK_P3TKc00")
                ->setPublishedAt(new DateTime("now"));
        return $formation;
    }
    
    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");        
    }
    
    public function testFindAllOrderBy(){
        $repository = $this->recupRepository();

        // vider la bdd de test pour ne tester que ce qu'on veut
        $formations = $repository->findAll();
        foreach ($formations as $f) {
            $repository->remove($f);
        }

        // Création de 2 formations avec des dates différentes
        $f1 = (new Formation())->setTitle("A")->setVideoId("111")->setPublishedAt(new DateTime("2020-01-01"));
        $f2 = (new Formation())->setTitle("B")->setVideoId("222")->setPublishedAt(new DateTime("2021-01-01"));

        $repository->add($f1);
        $repository->add($f2);

        // Tri ascendant
        $resultAsc = $repository->findAllOrderBy('publishedAt', 'ASC');
        $this->assertEquals("A", $resultAsc[0]->getTitle(), "Erreur : le tri ASC n'est pas respecté.");

        // Tri descendant
        $resultDesc = $repository->findAllOrderBy('publishedAt', 'DESC');
        $this->assertEquals("B", $resultDesc[0]->getTitle(), "Erreur : le tri DESC n'est pas respecté.");
    }
    
    public function testFindByContainValue(){
        $repository = $this->recupRepository();

        $formation = (new Formation())->setTitle("Symfony pour débutants")
                ->setVideoId("544534")->setPublishedAt(new DateTime("now"));

        $repository->add($formation);

        $result = $repository->findByContainValue("title", "Symfony");
        $this->assertCount(1, $result, 
                "Erreur : le filtre 'Symfony' ne retourne pas le bon nombre de résultats.");
        $this->assertEquals("Symfony pour débutants", $result[0]->getTitle());
    }
    
        
    public function testFindAllLasted(){
        $repository = $this->recupRepository();
        foreach ($repository->findAll() as $f) {
            $repository->remove($f);
        }

        $f1 = (new Formation())->setTitle("Ancienne formation")
                ->setVideoId("111")->setPublishedAt(new DateTime("2020-01-01"));
        $f2 = (new Formation())->setTitle("Formation récente")
                ->setVideoId("222")->setPublishedAt(new DateTime("2023-01-01"));
        $repository->add($f1);
        $repository->add($f2);

        $result = $repository->findAllLasted();

        $this->assertEquals("Formation récente",
                $result[0]->getTitle(),
                "Erreur : la formation la plus récente n'est pas en premier.");
    }
    
    public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        foreach ($repository->findAll() as $f) {
            $repository->remove($f);
        }

        $em = self::getContainer()->get('doctrine')->getManager();

        // Playlist persistée AVANT utilisation
        $playlist = new Playlist();
        $playlist->setName("Playlist Test");
        $em->persist($playlist);
        $em->flush();

        $f1 = (new Formation())
            ->setTitle("Formation liée")
            ->setVideoId("111")
            ->setPublishedAt(new DateTime("2021-01-01"))
            ->setPlaylist($playlist);

        $f2 = (new Formation())
            ->setTitle("Formation non liée")
            ->setVideoId("222")
            ->setPublishedAt(new DateTime("2022-01-01"));

        $repository->add($f1);
        $repository->add($f2);

        $result = $repository->findAllForOnePlaylist($playlist->getId());

        $this->assertNotEmpty($result, "Erreur : aucune formation trouvée pour la playlist.");
        $this->assertEquals("Formation liée", $result[0]->getTitle());
    }
    
}
