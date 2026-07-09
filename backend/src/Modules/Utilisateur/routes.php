<?php


use App\Modules\Utilisateur\UtilisateurController;

$router->post('/api/utilisateurs/inscription', [UtilisateurController::class, 'inscription']);
$router->post('/api/utilisateurs/connexion',   [UtilisateurController::class, 'connexion']);
$router->post(  '/api/utilisateurs/deconnexion',   [UtilisateurController::class, 'deconnexion']);
$router->get(   '/api/utilisateurs/profil',         [UtilisateurController::class, 'profil']);
$router->put(   '/api/utilisateurs/profil',         [UtilisateurController::class, 'modifierProfil']);
$router->put(   '/api/utilisateurs/mot-de-passe',   [UtilisateurController::class, 'modifierMotDePasse']);
$router->delete('/api/utilisateurs/compte',         [UtilisateurController::class, 'supprimerCompte']);
