<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\Workspace;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Workspace>
 *
 * @method Workspace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workspace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workspace[]    findAll()
 * @method Workspace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkspaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workspace::class);
    }
    public function removeOrphanReviews(Workspace $workspace): void
    {
        $reviews = $workspace->getReviews()->filter(function (Review $review) {
            return $review->getWorkspace() === null;
        });

        foreach ($reviews as $review) {
            $this->remove($review);
        }
    }
//    /**
//     * @return Workspace[] Returns an array of Workspace objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workspace
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}