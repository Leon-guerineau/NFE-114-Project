<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class PostRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Post::class));
    }

    public function getRecent($nbResults): array
    {
        return $this
            ->createQueryBuilder('element')
            ->setMaxResults($nbResults)
            ->getQuery()
            ->getResult();
    }
}
