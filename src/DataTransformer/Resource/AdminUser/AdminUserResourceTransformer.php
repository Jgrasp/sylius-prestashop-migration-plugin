<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\AdminUser;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Employee\EmployeeModel;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Security\PasswordUpdaterInterface;

class AdminUserResourceTransformer implements ResourceTransformerInterface
{
    private ResourceTransformerInterface $transformer;

    private PasswordUpdaterInterface $passwordUpdater;

    private RepositoryInterface $localeRepository;

    public function __construct(ResourceTransformerInterface $transformer, PasswordUpdaterInterface $passwordUpdater, RepositoryInterface $localeRepository)
    {
        $this->transformer = $transformer;
        $this->passwordUpdater = $passwordUpdater;
        $this->localeRepository = $localeRepository;
    }

    /**
     * @param EmployeeModel $model
     *
     * @return ResourceInterface
     * @throws Exception
     */
    public function transform(ModelInterface $model): ResourceInterface
    {
        /**
         * @var AdminUserInterface $adminUser
         */
        $adminUser = $this->transformer->transform($model);

        $adminUser->setPlainPassword('sylius');
        $this->passwordUpdater->updatePassword($adminUser);

        $locale = $this->localeRepository->findOneBy(['prestashopId' => $model->langId]);
        $adminUser->setLocaleCode($locale->getCode());

        return $adminUser;
    }

}
