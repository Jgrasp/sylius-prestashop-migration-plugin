<?php

namespace Jgrasp\PrestashopMigrationPlugin\Repository;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class EntityRepository implements EntityRepositoryInterface
{
    private Connection $connection;

    private string $_prefix;

    private string $_entity;

    private string $_primaryKey;

    public function __construct(string $_entity, string $_prefix, string $_primaryKey, Connection $connection)
    {
        $this->_entity = $_entity;
        $this->_prefix = $_prefix;
        $this->_primaryKey = $_primaryKey;
        $this->connection = $connection;
    }

    public function findAll(int $limit = 10, int $offset = 0): array
    {
        $query = $this
            ->createQueryBuilder()
            ->select('*')
            ->from($this->getTable())
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $this->fetchAllAssociative($query);
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    protected function fetchAllAssociative(QueryBuilder $query): array
    {
        return $this->connection->executeQuery($query)->fetchAllAssociative();
    }

    protected function getTable(): string
    {
        return $this->_prefix.$this->_entity;
    }

    protected function getTableAlias(): string
    {
        return $this->getTable();
    }

    protected function getPrimaryKey(): string
    {
        return $this->_primaryKey;
    }
}
