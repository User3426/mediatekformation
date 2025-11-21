<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of PlaylistsControllerTest
 *
 * @author Tristan
 */
class PlaylistsControllerTest extends WebTestCase{
    
    public function testTriTitre(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertAnySelectorTextContains('h5', 
                "Bases de la programmation (C#)");
    }
    
    public function testTriNbformations(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/nbformations/DESC');
        $this->assertAnySelectorTextContains('h5', 
                "Bases de la programmation (C#)");
    }
    
    public function testLinkPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        // clic sur un lien
        $client->clickLink('Voir détail');
        // récupération du résultat du clic
        $response = $client->getResponse();
        //contrôle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et contrôle qu'elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
    }
    
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        //simulation de la soumission du formulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Bases de la programmation (C#)'
        ]);
        // verifie le nombre de ligne obtenue
        $this->assertCount(1, $crawler->filter('h5'));
        // verifie le nom de la playlist
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
}
