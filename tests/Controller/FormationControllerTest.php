<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationControllerTest
 *
 * @author Tristan
 */
class FormationControllerTest extends WebTestCase{
    
    /*
     * Test l'accès à la page d'acceuil
     */
    public function testAccesPageAcceuil(){
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testTriDatesDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertAnySelectorTextContains('h5', 'Eclipse n°5 : Refactoring');
    }
    
    public function testTriDatesASC(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertAnySelectorTextContains('h5', 
                "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
    }
    
    public function testTriTitre(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        $this->assertAnySelectorTextContains('h5', 
                "Android Studio (complément n°1) : Navigation Drawer et Fragment");
    }
    
    public function testTriPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertAnySelectorTextContains('h5', 
                "Bases de la programmation n°74 - POO : collections");
    }
    
    public function testLinkFormation(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        // clic sur un lien
        $link = $crawler->filter('a[href*="formations/formation/"]')->first()->link();
        $client->click($link);
        // récupération du résultat du clic
        $response = $client->getResponse();
        //contrôle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et contrôle qu'elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/4', $uri);
    }
    
    public function testFiltreFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse n°5 : Refactoring'
        ]);
        // verifie le nombre de ligne obtenue
        $this->assertCount(1, $crawler->filter('h5'));
        // verifie le nom de la playlist
        $this->assertSelectorTextContains('h5', 'Eclipse n°5 : Refactoring');
    }
    
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        // récupérer le 2e formulaire "filtrer"
        $form = $crawler
            ->filter('th:nth-child(2) form')   // la colonne playlist
            ->form([
                'recherche' => 'Sujet E5 SLAM 2019 métropole : cas RESTILOC'
            ]);

        $crawler = $client->submit($form);
        // verifie le nombre de ligne obtenue
        $this->assertCount(4, $crawler->filter('h5'));
        // verifie le nom de la playlist
        $this->assertSelectorTextContains('h5', 'Sujet E5 SLAM 2019 : cas RESTILOC mission4 (calcul et comparatif)');
    }
}
