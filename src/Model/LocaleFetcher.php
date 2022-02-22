<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class LocaleFetcher
{
    private EntityRepositoryInterface $entityRepository;

    private RepositoryInterface $resourceRepository;

    private array $localeCached;

    public function __construct(EntityRepositoryInterface $entityRepository, RepositoryInterface $resourceRepository)
    {
        $this->entityRepository = $entityRepository;
        $this->resourceRepository = $resourceRepository;
        $this->localeCached = [];
    }

    /**
     * @return LocaleInterface[]
     * @throws \Exception
     */
    public function getLocales(): array
    {
        $languages = $this->entityRepository->findAll();

        foreach ($languages as $language) {
            $this->getLocale((int) $language['id_lang']);
        }

        return array_filter($this->localeCached, static fn($locale) => null !== $locale);
    }

    public function getLocale(int $languageId): ?LocaleInterface
    {
        if (array_key_exists($languageId, $this->localeCached)) {
            return $this->localeCached[$languageId];
        }

        $language = $this->entityRepository->find($languageId);

        if (empty($language) || !array_key_exists('locale', $language)) {
            throw new \Exception(sprintf("Lang %s does not exist.", $languageId));
        }

        $code = StringInflector::nameToCode($language['locale']);
        $locale = $this->resourceRepository->findOneBy(['code' => $code]);

        $this->localeCached[$languageId] = $locale;

        return $this->localeCached[$languageId];
    }

    public function getLocaleCode(int $langId): ?string
    {
        $locale = $this->getLocale($langId);

        return $locale?->getCode();
    }
}
