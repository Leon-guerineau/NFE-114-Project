<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 * @method Comment[] findAll()
 */
class CommentRepository extends EntityRepository
{
  public function __construct(EntityManagerInterface $em)
  {
    parent::__construct($em, new ClassMetadata(Comment::class));
  }
}