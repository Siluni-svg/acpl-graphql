<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Video> */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    /** @return Video[] */
    public function findByPublishedAtDesc(\DateTime $from, int $limit = 10): array
    {
        $video = $this->createQueryBuilder('y')
            ->where('y.enabled = :enabled')
            ->andWhere('y.published_at >= :from')
            ->setParameter('enabled', true)
            ->setParameter('from', $from)
            ->getQuery()
            ->getResult();

        // Shuffle > Limit > Sort
        shuffle($video);
        $video = array_slice($video, 0, $limit);
        usort($video, function ($a, $b) {
            return $b->getPublishedAt() <=> $a->getPublishedAt();
        });

        return $video;
    }

    /** @return list<string> */
    public function findAllVideoIds(\DateTime $from): array
    {
        $videos = $this->createQueryBuilder('y')
            ->select('y.platform_video_id')
            ->andWhere('y.published_at >= :from')
            ->setParameter('from', $from)
            ->getQuery()
            ->getResult();

        return array_column($videos, 'platform_video_id');
    }

    public function deleteOldVideo(\DateTimeInterface $since): void
    {
        $videos = $this->createQueryBuilder('y')
            ->select('y.platform_video_id')
            ->andWhere('y.published_at < :since')
            ->setParameter('since', $since)
            ->getQuery()
            ->getResult();

        foreach ($videos as $video) {
            $this->getEntityManager()->remove($video);
        }

        $this->getEntityManager()->flush();
    }
}
