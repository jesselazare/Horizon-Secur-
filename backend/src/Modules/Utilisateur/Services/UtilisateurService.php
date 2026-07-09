<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur\Services;

use App\Core\Response;
use App\Modules\Utilisateur\Repositories\UtilisateurRepository;
use App\Modules\Utilisateur\Models\Utilisateur;

final class UtilisateurService
{
    private UtilisateurRepository $repository;

    public function __construct()
    {
        $this->repository = new UtilisateurRepository();
    }


    public function inscrire(array $data): Utilisateur
    {
        $nom       = trim($data['nom'] ?? '');
        $prenom     = trim($data['prenom'] ?? '');
        $email     = trim($data['email'] ?? '');
        $adresse     = trim($data['adresse'] ?? '');
        $password  = $data['password'] ?? '';
        $telephone = trim($data['telephone'] ?? '') ?: null;

        if (empty($nom) || empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Les champs nom, email et password sont obligatoires.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Adresse email invalide.');
        }

        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères.');
        }

        if ($this->repository->emailExists($email)) {
            throw new \InvalidArgumentException('Un compte existe déjà avec cette adresse email.');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $id             = $this->repository->create($nom, $prenom, $email, $adresse, $hashedPassword, $telephone);

     
        $utilisateur = $this->repository->findById($id);

        if ($utilisateur === null) {
            throw new \RuntimeException('Erreur lors de la création du compte.');
        }

        return $utilisateur;
    }

  
    public function connecter(string $email, string $password): Utilisateur
    {
        $email = trim($email);

        if (empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Email et mot de passe requis.');
        }

       
        $utilisateur = $this->repository->findByEmail($email);

        if (!$utilisateur) {
            throw new \InvalidArgumentException('Email ou mot de passe incorrect.');
        }

        // BUG CORRIGÉ : vérification statut avant password_verify
        if ($utilisateur->statut === 'banni') {
            throw new \InvalidArgumentException('Ce compte a été suspendu.');
        }

        if ($utilisateur->statut === 'supprime') {
            throw new \InvalidArgumentException('Ce compte n\'existe plus.');
        }

        if (!password_verify($password, $utilisateur->password)) {
            throw new \InvalidArgumentException('Email ou mot de passe incorrect.');
        }

        $this->startSession($utilisateur);

        return $utilisateur;
    }

   

    public function deconnecter(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

  
    public function getProfil(int $Id): Utilisateur
    {
        $utilisateur = $this->repository->findById($Id);

        if ($utilisateur === null) {
            throw new \RuntimeException('Utilisateur introuvable.');
        }

        return $utilisateur;
    }

    public function modifierProfil(int $Id, array $data): Utilisateur
    {
        $nom       = trim($data['nom'] ?? '');
        $prenom     = trim($data['prenom'] ?? '');
        $email     = trim($data['email'] ?? '');
        $adresse     = trim($data['adresse'] ?? '');
        $telephone = trim($data['telephone'] ?? '') ?: null;

        if (empty($nom) || empty($email)) {
            throw new \InvalidArgumentException('Les champs nom et email sont obligatoires.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Adresse email invalide.');
        }

        if ($this->repository->emailExists($email, $Id)) {
            throw new \InvalidArgumentException('Cette adresse email est déjà utilisée.');
        }

        $this->repository->updateProfil($Id, $nom,$prenom, $email, $adresse, $telephone);

        $utilisateur = $this->repository->findById($Id);

        if ($utilisateur === null) {
            throw new \RuntimeException('Erreur lors de la mise à jour du profil.');
        }

        return $utilisateur;
    }

    public function modifierMotDePasse(int $Id, string $ancienPassword, string $nouveauPassword): void
    {
        $utilisateur = $this->repository->findById($Id);

        if ($utilisateur === null) {
            throw new \RuntimeException('Utilisateur introuvable.');
        }

        if (!password_verify($ancienPassword, $utilisateur->password)) {
            throw new \InvalidArgumentException('Ancien mot de passe incorrect.');
        }

        if (strlen($nouveauPassword) < 8) {
            throw new \InvalidArgumentException('Le nouveau mot de passe doit contenir au moins 8 caractères.');
        }

        $hashed = password_hash($nouveauPassword, PASSWORD_BCRYPT);
        $this->repository->updatePassword($Id, $hashed);
    }

    public function supprimerCompte(int $Id, string $password): void
    {
        $utilisateur = $this->repository->findById($Id);

        if ($utilisateur === null) {
            throw new \RuntimeException('Utilisateur introuvable.');
        }

        if (!password_verify($password, $utilisateur->mot_de_passe)) {
            throw new \InvalidArgumentException('Mot de passe incorrect. Suppression annulée.');
        }

        $this->repository->delete($Id);
        $this->deconnecter();
    }

    

    private function startSession(Utilisateur $utilisateur): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['id']    = $utilisateur->id;
        $_SESSION['nom']   = $utilisateur->nom;
        $_SESSION['email'] = $utilisateur->email;
    }

    public function getSessionClientId(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['id']) ? (int) $_SESSION['id'] : null;
    }

    public function isConnecte(): bool
    {
        return $this->getSessionClientId() !== null;
    }
}
