<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur;

class UtilisateurService
{
    private UtilisateurRepository $repository;

    public function __construct()
    {
        $this->repository = new UtilisateurRepository();
    }

    // ── Inscription ───────────────────────────────────────────────────────────

    /**
     * Crée un nouveau compte client.
     * RG-03 : la création de compte est proposée après chaque achat validé.
     *
     * @throws \InvalidArgumentException
     */
    public function inscrire(array $data): Utilisateur
    {
        $nom       = trim($data['nom'] ?? '');
        $email     = trim($data['email'] ?? '');
        $password  = $data['mot_de_passe'] ?? '';
        $telephone = trim($data['telephone'] ?? '') ?: null;

        // Validations
        if (empty($nom) || empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Les champs nom, email et mot_de_passe sont obligatoires.');
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
        $id = $this->repository->create($nom, $email, $hashedPassword, $telephone);

        return $this->repository->findById($id);
    }

    // ── Connexion ─────────────────────────────────────────────────────────────

    /**
     * Authentifie un client et démarre sa session.
     *
     * @throws \InvalidArgumentException
     */
    public function connecter(string $email, string $password): Utilisateur
    {
        $email = trim($email);

        if (empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Email et mot de passe requis.');
        }

        $utilisateur = $this->repository->findByEmail($email);

        if (!$utilisateur || !password_verify($password, $utilisateur->mot_de_passe)) {
            throw new \InvalidArgumentException('Email ou mot de passe incorrect.');
        }

        if ($utilisateur->statut_compte === 'banni') {
            throw new \InvalidArgumentException('Ce compte a été suspendu.');
        }

        if ($utilisateur->statut_compte === 'supprime') {
            throw new \InvalidArgumentException('Ce compte n\'existe plus.');
        }

        // Démarrage session
        $this->startSession($utilisateur);

        return $utilisateur;
    }

    // ── Déconnexion ───────────────────────────────────────────────────────────

    public function deconnecter(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    // ── Profil ────────────────────────────────────────────────────────────────

    /**
     * Retourne le profil du client connecté.
     * RG-04 : un client peut modifier ses informations depuis son espace compte.
     *
     * @throws \RuntimeException
     */
    public function getProfil(int $clientId): Utilisateur
    {
        $utilisateur = $this->repository->findById($clientId);

        if (!$utilisateur) {
            throw new \RuntimeException('Utilisateur introuvable.');
        }

        return $utilisateur;
    }

    // ── Modification ──────────────────────────────────────────────────────────

    /**
     * Met à jour les informations personnelles du client.
     *
     * @throws \InvalidArgumentException|\RuntimeException
     */
    public function modifierProfil(int $clientId, array $data): Utilisateur
    {
        $nom       = trim($data['nom'] ?? '');
        $email     = trim($data['email'] ?? '');
        $telephone = trim($data['telephone'] ?? '') ?: null;

        if (empty($nom) || empty($email)) {
            throw new \InvalidArgumentException('Les champs nom et email sont obligatoires.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Adresse email invalide.');
        }

        if ($this->repository->emailExists($email, $clientId)) {
            throw new \InvalidArgumentException('Cette adresse email est déjà utilisée.');
        }

        $this->repository->updateProfil($clientId, $nom, $email, $telephone);

        return $this->repository->findById($clientId);
    }

    /**
     * Met à jour le mot de passe du client.
     *
     * @throws \InvalidArgumentException
     */
    public function modifierMotDePasse(int $clientId, string $ancienPassword, string $nouveauPassword): void
    {
        $utilisateur = $this->repository->findById($clientId);

        if (!$utilisateur) {
            throw new \RuntimeException('Utilisateur introuvable.');
        }

        if (!password_verify($ancienPassword, $utilisateur->mot_de_passe)) {
            throw new \InvalidArgumentException('Ancien mot de passe incorrect.');
        }

        if (strlen($nouveauPassword) < 8) {
            throw new \InvalidArgumentException('Le nouveau mot de passe doit contenir au moins 8 caractères.');
        }

        $hashed = password_hash($nouveauPassword, PASSWORD_BCRYPT);
        $this->repository->updatePassword($clientId, $hashed);
    }

    // ── Suppression ───────────────────────────────────────────────────────────

    /**
     * Supprime définitivement le compte du client.
     * RG-05 : la suppression est définitive et efface toutes les données après confirmation.
     *
     * @throws \InvalidArgumentException|\RuntimeException
     */
    public function supprimerCompte(int $clientId, string $password): void
    {
        $utilisateur = $this->repository->findById($clientId);

        if (!$utilisateur) {
            throw new \RuntimeException('Utilisateur introuvable.');
        }

        if (!password_verify($password, $utilisateur->mot_de_passe)) {
            throw new \InvalidArgumentException('Mot de passe incorrect. Suppression annulée.');
        }

        $this->repository->delete($clientId);
        $this->deconnecter();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function startSession(Utilisateur $utilisateur): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['client_id']  = $utilisateur->id_client;
        $_SESSION['client_nom'] = $utilisateur->nom;
        $_SESSION['client_email'] = $utilisateur->email;
    }

    public function getSessionClientId(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['client_id']) ? (int) $_SESSION['client_id'] : null;
    }

    public function isConnecte(): bool
    {
        return $this->getSessionClientId() !== null;
    }
}
