<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur\Repositories;

use App\Core\Database;
use App\Modules\Utilisateur\Models\Utilisateur;
use PDO;

 final class UtilisateurRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM utilisateur
             WHERE id = :id
               AND statut NOT IN ('banni', 'supprime')
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM utilisateur WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql    = "SELECT COUNT(*) FROM utilisateur WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql           .= " AND id != :id";
            $params['id']   = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

  

    public function create(string $nom, string $prenom, string $email, string $adresse, string $hashedPassword, ?string $telephone): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO utilisateur (nom, prenom, email, adresse, password, telephone, date_inscription, statut)
             VALUES (:nom, :prenom, :email, :adresse, :password, :telephone, NOW(), 'actif')"
        );
        $stmt->execute([
            'nom'       => $nom,
            'prenom'    => $prenom, 
            'email'     => $email,
            'adresse'   => $adresse,
            'password'  => $hashedPassword,
            'telephone' => $telephone,
        ]);

        return (int) $this->db->lastInsertId();
    }

// ── Mise à jour ───────────────────────────────────────────────────────────

    public function updateProfil(int $id, string $nom, string $prenom, string $email, string $adresse, ?string $telephone): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE utilisateur
             SET nom = :nom, prenom = :prenom, email = :email, adresse = :adresse, telephone = :telephone
             WHERE id = :id"
        );
        return $stmt->execute([
            'nom'       => $nom,
            'prenom'    => $prenom, 
            'email'     => $email,
            'adresse'   => $adresse,
            'telephone' => $telephone,
            'id'        => $id,
        ]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE utilisateur SET mot_de_passe = :password WHERE id = :id"
        );
        return $stmt->execute([
            'password' => $hashedPassword,
            'id'       => $id,
        ]);
    }

  

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE utilisateur SET statut = 'supprime' WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }
}
