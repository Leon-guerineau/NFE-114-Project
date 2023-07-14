<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class GameRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Game::class));
    }

    public function getRecent($nbResults): array
    {
        return $this
            ->createQueryBuilder('game')
            ->setMaxResults($nbResults)
            ->getQuery()
            ->getResult()
        ;
    }
}
