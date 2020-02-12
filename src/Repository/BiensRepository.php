<?php

namespace App\Repository;

use App\Entity\Biens;
use App\Form\BiensType;
use Doctrine\ORM\Query;
use App\Entity\Recherche;
use App\Form\RechercheType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Biens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biens[]    findAll()
 * @method Biens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biens::class);
    }


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Recherche $recherche): Query
    {
        $query = $this->findVisibleQuery();

        if($recherche->getMaxPrix()) {
            $query = $query
                ->andwhere('p.prix >= :maxprix')
                ->setParameter('maxprix', $recherche->getMaxPrix());
        }

        if($recherche->getMinSurface()) {
            $query = $query
                ->andwhere('p.surface <= :minsurf')
                ->setParameter('minsurf', $recherche->getMinSurface());
        }

        if($recherche->getOptions()->count() > 0) {
            $k = 0;
            foreach($recherche->getOptions() as $option) {
                $k++;  
                $query = $query
                ->andwhere(":option$k MEMBER OF p.options")
                ->setParameter("option$k", $option);
            }
          
        }

        return $query->getQuery();
    }

    /**
     * @return Biens[]
     */
    public function findLatest($entity)
    {
        return $this->getEntityManager()->createQuery("
        SELECT e FROM $entity e ORDER BY e.id DESC")
            ->setMaxResults(4)
            ->getResult();

    }

    public function Demeures($entity, $demeures) {

        return $this->getEntityManager()->createQuery("
        SELECT e FROM $entity e WHERE e.titre LIKE '$demeures%'")
            ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where( 'p.vendu = false' );
    }

    // /**
    //  * @return Biens[] Returns an array of Biens objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Biens
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
