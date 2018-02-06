<?php

namespace App\Repository;

use App\Entity\VariableAttributeCategory;
use App\Entity\VariableAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VariableAttributeCategoryRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, VariableAttributeCategory::class);
	}

	public function findCheapest(VariableAttributeCategory $category) {
		$repository = $this
		->getEntityManager()
		->getRepository(VariableAttribute::class);
		return $repository->findCheapest($category);
	}
}
