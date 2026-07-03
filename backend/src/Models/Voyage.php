<?php

declare(strict_types=1);

namespace App\Models;

final class Voyage
{
    public function __construct(
        public readonly int $idVoyage,
        public readonly string $destination,
        public readonly float $prix,
        public readonly string $dateDepart,
        public readonly ?string $titre = null,
        public readonly ?string $pays = null,
        public readonly ?int $capaciteMax = null,
        public readonly ?string $imageUrl = null,
        public readonly ?string $description = null,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id_voyage'],
            (string) $row['destination'],
            (float) $row['prix'],
            (string) $row['date_depart'],
            isset($row['titre']) ? (string) $row['titre'] : null,
            isset($row['pays']) ? (string) $row['pays'] : null,
            isset($row['capacite_max']) ? (int) $row['capacite_max'] : null,
            isset($row['image_url']) ? (string) $row['image_url'] : null,
            isset($row['description']) ? (string) $row['description'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'id_voyage' => $this->idVoyage,
            'titre' => $this->titre,
            'destination' => $this->destination,
            'pays' => $this->pays,
            'prix' => $this->prix,
            'date_depart' => $this->dateDepart,
            'capacite_max' => $this->capaciteMax,
            'image_url' => $this->imageUrl,
            'description' => $this->description,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
