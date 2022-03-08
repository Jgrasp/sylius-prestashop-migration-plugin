<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Validator;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ViolationBag implements ViolationBagInterface
{
    private LocaleContextInterface $localeContext;

    private TranslatorInterface $translator;

    public function __construct(LocaleContextInterface $localeContext, TranslatorInterface $translator)
    {
        $this->localeContext = $localeContext;
        $this->translator = $translator;

    }

    /**
     * @var Violation[]
     */
    private array $violations = [];

    public function all(): array
    {
        $violations = $this->violations;
        $this->violations = [];

        return $violations;
    }

    public function addViolation(Violation $violation): void
    {
        $this->violations[] = $violation;
    }

    public function addConstraintViolationList(int $entityId, ConstraintViolationListInterface $constraintViolationList): void
    {
        array_walk_recursive(
            $constraintViolationList,
            fn(ConstraintViolationInterface $constraintViolation) => $this->addViolation(new Violation($entityId, $this->translator->trans($constraintViolation->getMessage(), $constraintViolation->getParameters())))
        );
    }


}
