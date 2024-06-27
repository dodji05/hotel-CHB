<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class UniqueIdentifierGenerator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateUniqueIdentifier(string $entityClass, string $identifierColumn, string $prefix = 'Cl', int $maxAttempts = 10000000): string
    {
        $NPro = 1;
        $v = sprintf("%s%08d", $prefix, $NPro);

        while ($this->identifierExists($entityClass, $identifierColumn, $v)) {
            $NPro++;
            if ($NPro >= $maxAttempts) {
                throw new \Exception("Veuillez consulter le concepteur.");
            }
            $v = sprintf("%s%08d", $prefix, $NPro);
        }

        return $v;
    }

    private function identifierExists(string $entityClass, string $identifierColumn, string $identifier): bool
    {
        $repository = $this->entityManager->getRepository($entityClass);
        $entity = $repository->findOneBy([$identifierColumn => $identifier]);

        return $entity !== null;
    }
}
