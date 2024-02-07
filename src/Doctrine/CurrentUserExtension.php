<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Application;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        Operation $operation = null,
        array $context = []
    ): void {
        if ($resourceClass !== Application::class || $this->security->getUser() === null) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $this->addRoleClientConditions($queryBuilder, $rootAlias);
    }

    private function addRoleClientConditions(QueryBuilder $queryBuilder, string $rootAlias): void
    {
        if ($this->security->isGranted('ROLE_CLIENT')) {
            $queryBuilder->andWhere(sprintf('%s.owner = :current_user', $rootAlias));
            $queryBuilder->setParameter('current_user', $this->security->getUser()->getId());
        }
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        if ($resourceClass !== Application::class || $this->security->getUser() === null) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $this->addRoleClientConditions($queryBuilder, $rootAlias);
    }
}