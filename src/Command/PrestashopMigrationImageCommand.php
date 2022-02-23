<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\Downloader\ImageDownloader;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\Product\ProductRepository;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class PrestashopMigrationImageCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private RepositoryInterface $resourceRepository;

    /**
     * @var ProductRepository $entityRepository
     */
    private EntityRepositoryInterface $entityRepository;

    private ImageDownloader $downloader;

    private FactoryInterface $productImageFactory;

    private ImageUploaderInterface $imageUploader;

    public function __construct(
        EntityManagerInterface    $entityManager,
        RepositoryInterface       $resourceRepository,
        EntityRepositoryInterface $entityRepository,
        ImageDownloader           $downloader,
        FactoryInterface          $productImageFactory,
        ImageUploaderInterface    $imageUploader
    )
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->resourceRepository = $resourceRepository;
        $this->entityRepository = $entityRepository;
        $this->downloader = $downloader;
        $this->productImageFactory = $productImageFactory;
        $this->imageUploader = $imageUploader;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $products = $this->resourceRepository->findAll();

        $progressBar = new ProgressBar($output, count($products));

        foreach ($products as $product) {
            $progressBar->advance();

            if (!$product->getPrestashopId()) {
                continue;
            }

            foreach ($product->getImages() as $productImage) {
                $product->removeImage($productImage);
            }

            $images = $this->entityRepository->getImages($product->getPrestashopId());

            foreach ($images as $image) {

                $path = $this->downloader->download((int)$image['id_image']);

                if (null !== $path) {
                    /** @var ProductImageInterface $productImage */
                    $productImage = $this->productImageFactory->createNew();
                    $productImage->setFile(new UploadedFile($path, basename($path)));

                    $this->imageUploader->upload($productImage);
                    $product->addImage($productImage);
                }
            }

            $this->entityManager->flush();
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }
}
