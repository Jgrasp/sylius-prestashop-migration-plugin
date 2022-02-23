<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;

trait UrlModelTrait
{
    use TranslationModelTrait;

    #[Field(source: 'link_rewrite', translatable: true)]
    public array $slug;

    public function getSlug(string $locale): ?string
    {
        return $this->getTranslation($this->slug, $locale);
    }
}
