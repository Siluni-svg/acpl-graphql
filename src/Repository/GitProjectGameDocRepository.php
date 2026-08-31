<?php

namespace App\Repository;

use App\Entity\GitProjectGameDoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GitProjectGameDoc> */
class GitProjectGameDocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitProjectGameDoc::class);
    }
}
