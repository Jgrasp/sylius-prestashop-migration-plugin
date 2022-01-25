<?php

namespace Jgrasp\PrestashopMigrationPlugin\Repository;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class EntityRepository
{
    private Connection $connection;

    private string $_prefix;

    private string $_entity;

    public function __construct(string $_entity, string $_prefix, Connection $connection)
    {
        $this->_entity = $_entity;
        $this->_prefix = $_prefix;
        $this->connection = $connection;
    }

    public function findAll(int $limit = null, int $offset = null): array
    {
        $query = $this
            ->createQueryBuilder()
            ->select('*')
            ->from($this->getTable());

        if (null !== $limit) {
            $query->setMaxResults($limit);
        }

        if (null !== $offset) {
            $query->setFirstResult($offset);
        }

        return $this->connection->executeQuery($query)->fetchAllAssociative();
    }

    public function createQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    private function getTable()
    {
        return $this->_prefix.$this->_entity;
    }
}
