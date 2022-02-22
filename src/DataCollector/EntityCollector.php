<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataCollector;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class EntityCollector implements DataCollectorInterface
{
    private EntityRepositoryInterface $repository;

    private bool $translatable;

    public function __construct(bool $translatable, EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->translatable = $translatable;
    }

    public function collect(int $limit, int $offset): array
    {
        $data = $this->repository->findAll($limit, $offset);

        if ($this->translatable) {

            foreach ($data as &$row) {

                $translations = $this->repository->findTranslations($row[$this->repository->getPrimaryKey()]);

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
        }

        return $data;
    }

    public function size(): int
    {
        return $this->repository->count();
    }

}
