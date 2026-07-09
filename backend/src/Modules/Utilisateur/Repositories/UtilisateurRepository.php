<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur;

use App\Core\Database;
use PDO;

class UtilisateurRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM client
             WHERE id_client = :id
               AND statut_compte NOT IN ('banni', 'supprime')
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM client WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql    = "SELECT COUNT(*) FROM client WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql           .= " AND id_client != :id";
            $params['id']   = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

  

    public function create(string $nom, string $email, string $hashedPassword, ?string $telephone): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO client (nom, email, mot_de_passe, telephone, date_inscription, statut_compte)
             VALUES (:nom, :email, :password, :telephone, NOW(), 'actif')"
        );
        $stmt->execute([
            'nom'       => $nom,
            'email'     => $email,
            'password'  => $hashedPassword,
            'telephone' => $telephone,
        ]);

        return (int) $this->db->lastInsertId();
    }

// ── Mise à jour ───────────────────────────────────────────────────────────

    public function updateProfil(int $id, string $nom, string $email, ?string $telephone): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE client
             SET nom = :nom, email = :email, telephone = :telephone
             WHERE id_client = :id"
        );
        return $stmt->execute([
            'nom'       => $nom,
            'email'     => $email,
            'telephone' => $telephone,
            'id'        => $id,
        ]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE client SET mot_de_passe = :password WHERE id_client = :id"
        );
        return $stmt->execute([
            'password' => $hashedPassword,
            'id'       => $id,
        ]);
    }

  

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE client SET statut_compte = 'supprime' WHERE id_client = :id"
        );
        return $stmt->execute(['id' => $id]);
    }
}
