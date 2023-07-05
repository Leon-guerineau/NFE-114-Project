<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 * @method User[] findAll()
 */
class UserRepository extends EntityRepository
{
  public function __construct(EntityManagerInterface $em)
  {
    parent::__construct($em, new ClassMetadata(User::class));
  }
}
