<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class UserRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(User::class));
    }

    public function getRecent($nbResults): array
    {
        return $this
            ->createQueryBuilder('user')
            ->setMaxResults($nbResults)
            ->getQuery()
            ->getResult();
    }
}
