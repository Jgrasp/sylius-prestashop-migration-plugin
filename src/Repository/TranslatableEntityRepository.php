<?php

namespace Jgrasp\PrestashopMigrationPlugin\Repository;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class TranslatableEntityRepository extends EntityRepository
{
    const LANGUAGE_IDENTIFIER = 'id_lang';

    public function findByLangId(int $langId, int $limit = 10, int $offset = 0)
    {
        $query = $this
            ->createQueryBuilder()
            ->select('*')
            ->from($this->getTable());


        $query = $this->applyTranslatableTable($query, $langId);

        $query
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $this->fetchAllAssociative($query);
    }

    protected function getTableLang(): string
    {
        return sprintf('%s_%s', $this->getTable(), 'lang');
    }

    private function getTableLangAlias(): string
    {
        return $this->getTableLang();
    }

    private function applyTranslatableTable(QueryBuilder $query, int $langId): QueryBuilder
    {
        $conditions = [$this->getLangCondition($langId), $this->getTableCondition()];

        return $query
            ->join($this->getTable(), $this->getTableLang(), $this->getTableLangAlias(), implode(' AND ', $conditions));

    }

    private function getLangCondition(int $langId): string
    {
        return sprintf('%s.%s=%d', $this->getTableLangAlias(), self::LANGUAGE_IDENTIFIER, $langId);
    }

    private function getTableCondition(): string
    {
        return sprintf('%s.%s=%s.%s', $this->getTable(), $this->getPrimaryKey(), $this->getTableLangAlias(), $this->getPrimaryKey());
    }
}
