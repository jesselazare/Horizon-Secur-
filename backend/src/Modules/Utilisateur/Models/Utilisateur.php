<?php

declare(strict_types=1);

namespace App\Modules\Utilisateur\Models;

final class Utilisateur
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $nom,
        public readonly string  $email,
        public readonly string  $password,
        public readonly ?string $adresse,
        public readonly ?string $telephone,
        public readonly ?string $date_inscription,
        public readonly string  $statut,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id:         isset($data['id']) ? (int) $data['id'] : null,
            nom:               $data['nom'],
            email:             $data['email'],
            password:      $data['mot_de_passe'],
            adresse:           $data['adresse'] ?? null,
            telephone:         $data['telephone'] ?? null,
            date_inscription:  $data['date_inscription'] ?? null,
            statut:     $data['statut'] ?? 'actif',
        );
    }

    public function toPublicArray(): array
    {
        return [
            'id'        => $this->id,
            'nom'              => $this->nom,
            'prenom'           => $this->prenom,
            'email'            => $this->email,
            'adresse'          => $this->adresse,
            'telephone'        => $this->telephone,
            'date_inscription' => $this->date_inscription,
            'statut'    => $this->statut,
        ];
    }
}
