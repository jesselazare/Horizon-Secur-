<?php

declare(strict_types=1);

namespace App\Modules\Voyage\Models;

final class Voyage
{
    public function __construct(
        public readonly int $id,
        public readonly string $destination,
        public readonly string $dateDepart,
        public readonly float $prixParPersonne,
        public readonly int $capaciteMax,
        public readonly ?string $titre = null,
        public readonly ?string $pays = null,
        public readonly ?string $description = null,
        public readonly ?string $imageUrl = null,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['destination'],
            (string) $row['date_depart'],
            (float) $row['prix_par_personne'],
            (int) $row['capacite_max'],
            isset($row['titre']) ? (string) $row['titre'] : null,
            isset($row['pays']) ? (string) $row['pays'] : null,
            isset($row['description']) ? (string) $row['description'] : null,
            isset($row['image_url']) ? (string) $row['image_url'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'titre' => $this->titre,
            'destination' => $this->destination,
            'pays' => $this->pays,
            'date_depart' => $this->dateDepart,
            'prix_par_personne' => $this->prixParPersonne,
            'capacite_max' => $this->capaciteMax,
            'description' => $this->description,
            'image_url' => $this->imageUrl,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
