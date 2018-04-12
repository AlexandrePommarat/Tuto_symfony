<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class AvisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    public function test()
    {
        $query= $this->createQueryBuilder('a');
        $query->select('a');

        return $query->getQuery()->getResult();

    }
}
