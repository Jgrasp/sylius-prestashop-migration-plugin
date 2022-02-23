<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataCollector;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class EntityTranslatableCollector implements DataCollectorInterface
{
    private DataCollectorInterface $collector;

    private EntityRepositoryInterface $repository;

    public function __construct(DataCollectorInterface $collector, EntityRepositoryInterface $repository)
    {
        $this->collector = $collector;
        $this->repository = $repository;
    }

    public function collect(int $limit, int $offset): array
    {
        $data = $this->collector->collect($limit, $offset);

        foreach ($data as &$row) {
            $entityId = (int) $row[$this->repository->getPrimaryKey()];
            $translations = $this->repository->findTranslations($entityId);

            foreach ($translations as $translation) {

                $diff = array_diff_assoc($translation, $row);

                if (!array_key_exists('id_lang', $diff)) {
                    throw new \Exception('Entity is not a valid translatable because "id_lang" is missing.');
                }

                $langId = $diff['id_lang'];
                unset($diff['id_lang']);

                if (array_key_exists('id_shop', $diff)) {
                    unset($diff['id_shop']);
                }

                //Add translations

                foreach ($diff as $key => $value) {
                    if (!array_key_exists($key, $row)) {
                        $row[$key] = [];
                    }

                    $row[$key][$langId] = $value;
                }
            }
        }

        return $data;
    }

    public function size(): int
    {
        return $this->collector->size();
    }

}
