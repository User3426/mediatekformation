<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/*
 * Contrôleur de connexion.
 *
 * Gère l'affichage du formulaire de login et la déconnexion.
 */
class LoginController extends AbstractController
{
    /*
     * Affiche le formulaire de connexion.
     *
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // récupération éventuelle de l'erreur
        $error = $authenticationUtils->getLastAuthenticationError();
        // récupération éventuelle du dernier nom de login utilisé
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    
    /*
     * Déconnecte l'utilisateur
     *
     * @Route("/logout", name="logout")
     * @return void
     */
    #[Route('/logout', name: 'logout')]
    public function logout(){
        
    }
}
