<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Modules\Utilisateur\Services\UtilisateurService;
use PDOException;

class UtilisateurController extends Controller
{
    private UtilisateurService $service;
    public function __construct()
    {
        $this->service = new UtilisateurService();
    }

   
    public function inscription(): void
    {
        $input = $this->input();
        try {
            $utilisateur = $this->service->inscrire($input);
            Response::json([
                'message'     => 'Compte créé avec succès.',
                'utilisateur' => $utilisateur->toPublicArray(),
            ], 201);
        } catch (\InvalidArgumentException $e) {
            Response::error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            Response::error('Erreur serveur lors de la création du compte.', 500);
        }
    }

   
    public function connexion(): void
    {
        $input    = $this->input();
        $email    = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        try {
            $utilisateur = $this->service->connecter($email, $password);
            Response::json([
                'message'     => 'Connexion réussie.',
                'utilisateur' => $utilisateur->toPublicArray(),
            ]);
        } catch (\InvalidArgumentException $e) {
            Response::error($e->getMessage(), 401);
        } catch (\Throwable $e) {
            Response::error('Erreur serveur lors de la connexion.', 500);
        }
    }


    public function deconnexion(): void
    {
        $this->requireAuth();
        $this->service->deconnecter();
        Response::json(['message' => 'Déconnexion réussie.']);
    }

   
    public function profil(): void
    {
        $Id = $this->requireAuth();

        try {
            $utilisateur = $this->service->getProfil($Id);
            Response::json(['utilisateur' => $utilisateur->toPublicArray()]);
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 404);
        }
    }

    public function modifierProfil(): void
    {
        $Id = $this->requireAuth();
        $input    = $this->input();

        try {
            $utilisateur = $this->service->modifierProfil($Id, $input);
            Response::json([
                'message'     => 'Profil mis à jour avec succès.',
                'utilisateur' => $utilisateur->toPublicArray(),
            ]);
        } catch (\InvalidArgumentException $e) {
            Response::error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            Response::error('Erreur serveur lors de la mise à jour.', 500);
        }
    }


    public function modifierMotDePasse(): void
    {
        $Id = $this->requireAuth();
        $input    = $this->input();

        $ancien  = $input['ancien_mot_de_passe'] ?? '';
        $nouveau = $input['nouveau_mot_de_passe'] ?? '';

       
        if (empty($ancien) || empty($nouveau)) {
            Response::error('Les deux mots de passe sont requis.', 422);
            return;
        }

        try {
            $this->service->modifierMotDePasse($Id, $ancien, $nouveau);
            Response::json(['message' => 'Mot de passe mis à jour avec succès.']);
        } catch (\InvalidArgumentException $e) {
            Response::error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            Response::error('Erreur serveur lors du changement de mot de passe.', 500);
        }
    }


    public function supprimerCompte(): void
    {
        $Id = $this->requireAuth();
        $input    = $this->input();
        $password = $input['mot_de_passe'] ?? '';

       
        if (empty($password)) {
            Response::error('Le mot de passe est requis pour confirmer la suppression.', 422);
            return;
        }

        try {
            $this->service->supprimerCompte($Id, $password);
            Response::json(['message' => 'Compte supprimé définitivement.']);
        } catch (\InvalidArgumentException $e) {
            Response::error($e->getMessage(), 422);
        } catch (\RuntimeException $e) {
            Response::error($e->getMessage(), 404);
        } catch (\Throwable $e) {
            Response::error('Erreur serveur lors de la suppression.', 500);
        }
    }

    
    private function requireAuth(): int
    {
        $Id = $this->service->getSessionId();

        if ($Id === null) {
            Response::error('Non authentifié. Veuillez vous connecter.', 401);
            exit; // sécurité supplémentaire
        }

        return $Id;
    }
}
