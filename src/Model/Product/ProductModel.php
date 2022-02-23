<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Product;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\TranslationModelTrait;
use Jgrasp\PrestashopMigrationPlugin\Model\UrlModelTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class ProductModel implements ModelInterface
{
    use TranslationModelTrait, UrlModelTrait, ToggleableTrait;

    #[Field(source: 'id_product', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'id_category_default')]
    public int $categoryDefaultId;

    #[Field(source: 'name', target: 'name', translatable: true)]
    public array $name;

    #[Field(source: 'description', target: 'description', translatable: true)]
    public array $description;

    #[Field(source: 'reference', target: 'code')]
    public ?string $code;

    #[Field(source: 'price')]
    public float $price;

    public function getName(string $locale): ?string
    {
        return $this->getTranslation($this->name, $locale);
    }

    public function getDescription(string $locale): ?string
    {
        return $this->getTranslation($this->description, $locale);
    }
}
