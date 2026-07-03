<?php

declare(strict_types=1);

namespace App\Modules\Voyage\Repositories;

use App\Core\Database;
use App\Modules\Voyage\Models\Voyage;
use PDO;

final class VoyageRepository
{
    private const SELECT_COLUMNS = '
        id,
        destination,
        date_depart,
        prix_par_personne,
        capacite_max,
        titre,
        pays,
        description,
        image_url
    ';

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT ' . self::SELECT_COLUMNS . ' FROM voyage ORDER BY date_depart ASC, id ASC'
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static fn (array $row): array => Voyage::fromRow($row)->toArray(),
            $rows
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(string $destination, float $budgetMax, string $dateDepart): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ' . self::SELECT_COLUMNS . '
             FROM voyage
             WHERE destination LIKE :destination
               AND prix_par_personne <= :budget_max
               AND date_depart = :date_depart
             ORDER BY prix_par_personne ASC'
        );

        $stmt->execute([
            'destination' => '%' . $destination . '%',
            'budget_max' => $budgetMax,
            'date_depart' => $dateDepart,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static fn (array $row): array => Voyage::fromRow($row)->toArray(),
            $rows
        );
    }

    public function find(int $id): ?Voyage
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT ' . self::SELECT_COLUMNS . ' FROM voyage WHERE id = :id'
        );

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : Voyage::fromRow($row);
    }

    public function countReservations(int $idVoyage): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) AS total FROM reservation WHERE id_voyage = :id_voyage'
        );

        $stmt->execute(['id_voyage' => $idVoyage]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }
}
