<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Tax;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\TranslationModelTrait;

class TaxModel implements ModelInterface
{
    use TranslationModelTrait;

    #[Field(source: 'id_tax', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'name', translatable: true)]
    public array $name;

    #[Field(source: 'rate', target: 'amount')]
    public float $rate;

    public function getName(string $locale): ?string
    {
        return $this->getTranslation($this->name, $locale);
    }

    public function hasName(string $locale): bool
    {
        return null !== $this->getName($locale);
    }

}
