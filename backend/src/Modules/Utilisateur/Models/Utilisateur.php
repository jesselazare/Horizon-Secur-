<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur;

class Utilisateur
{
    public function __construct(
        public readonly ?int    $id_client,
        public readonly string  $nom,
        public readonly string  $email,
        public readonly string  $mot_de_passe,
        public readonly ?string $telephone,
        public readonly ?string $date_inscription,
        public readonly string  $statut_compte,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id_client:         isset($data['id_client']) ? (int) $data['id_client'] : null,
            nom:               $data['nom'],
            email:             $data['email'],
            mot_de_passe:      $data['mot_de_passe'],
            telephone:         $data['telephone'] ?? null,
            date_inscription:  $data['date_inscription'] ?? null,
            statut_compte:     $data['statut_compte'] ?? 'actif',
        );
    }

    public function toPublicArray(): array
    {
        return [
            'id_client'        => $this->id_client,
            'nom'              => $this->nom,
            'email'            => $this->email,
            'telephone'        => $this->telephone,
            'date_inscription' => $this->date_inscription,
            'statut_compte'    => $this->statut_compte,
        ];
    }
}
