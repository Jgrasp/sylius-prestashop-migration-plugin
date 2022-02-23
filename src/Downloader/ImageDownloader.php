<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Downloader;

use Symfony\Component\Filesystem\Filesystem;

class ImageDownloader
{
    private string $publicDirectory;

    private string $tmpDirectory;

    public function __construct(string $publicDirectory, string $tmpDirectory)
    {
        $this->publicDirectory = $publicDirectory;
        $this->tmpDirectory = $tmpDirectory;
    }

    public function download(int $imageId): ?string
    {
        $url = $this->getUrl($imageId);

        $image = @file_get_contents($url);

        if (false === $image) {
            return null;
        }

        $filesystem = new Filesystem();
        $filesystem->mkdir($this->tmpDirectory);

        $path = $this->getTmpPath($imageId);

        file_put_contents($path, $image);

        return $path;
    }

    private function getUrl(int $imageId): string
    {
        return sprintf('%s/%s', $this->publicDirectory, $this->getImageDirectory($imageId));
    }

    private function getImageDirectory(int $imageId): string
    {
        $directory = implode('/', str_split((string)$imageId));

        return sprintf('%s/%s.%s', $directory, $imageId, 'jpg');
    }

    private function getTmpPath(int $imageId): string
    {
        return $this->tmpDirectory.$imageId;
    }
}
