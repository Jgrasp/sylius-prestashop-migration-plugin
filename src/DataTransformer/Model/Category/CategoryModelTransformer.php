<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model\Category;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model\ModelTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Category\CategoryModel;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class CategoryModelTransformer implements ModelTransformerInterface
{
    private ModelTransformerInterface $transformer;

    private EntityRepositoryInterface $repository;

    public function __construct(ModelTransformerInterface $transformer, EntityRepositoryInterface $repository)
    {
        $this->transformer = $transformer;
        $this->repository = $repository;
    }

    public function transform(array $data): ModelInterface
    {
        /**
         * @var CategoryModel $model
         */
        $model = $this->transformer->transform($data);

        return $model;
    }

}
