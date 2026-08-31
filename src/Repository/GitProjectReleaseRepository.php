<?php

namespace App\Repository;

use App\Entity\GitProjectGame;
use App\Entity\GitProjectRelease;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GitProjectRelease> */
class GitProjectReleaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitProjectRelease::class);
    }

    /**
     * Latest releases that resolve to a published game, most recent first.
     *
     * Releases are scanned in batches (newest first) until enough of them
     * match a published GitProjectGame, so a run of releases with no
     * matching/published game doesn't shrink the result below $limit.
     *
     * @return list<array{release: GitProjectRelease, gitProjectGame: GitProjectGame}>
     */
    public function findLatestWithPublishedGame(int $limit = 5, int $batchSize = 200): array
    {
        $results = [];
        $offset = 0;

        do {
            $releases = $this->createQueryBuilder('r')
                ->innerJoin('r.git_project', 'gp')
                ->addSelect('gp')
                ->leftJoin('gp.gitProjectGames', 'gpg')
                ->addSelect('gpg')
                ->orderBy('r.published_at', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($batchSize)
                ->getQuery()
                ->getResult();

            foreach ($releases as $release) {
                $gitProjectGame = null;
                foreach ($release->getGitProject()->getGitProjectGames() as $candidate) {
                    if (StatusEnum::PUBLISHED !== $candidate->getStatus()) {
                        continue;
                    }

                    $tagRegex = $candidate->getTagRegex();
                    if (null === $tagRegex || 1 === preg_match($tagRegex, $release->getRelease())) {
                        $gitProjectGame = $candidate;
                        break;
                    }
                }

                if (null === $gitProjectGame) {
                    continue;
                }

                $results[] = ['release' => $release, 'gitProjectGame' => $gitProjectGame];

                if (\count($results) >= $limit) {
                    return $results;
                }
            }

            $offset += $batchSize;
        } while (\count($releases) === $batchSize);

        return $results;
    }
}
