<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Plants;
use Doctrine\ORM\Query;
use App\Entity\PlantSearch;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Plants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plants[]    findAll()
 * @method Plants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plants::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Plants $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Plants $entity, bool $flush = true): void
    {
        
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Query
     */
    public function FindAllVisibleQuery(PlantSearch $search) : Query
    {
                    $query = $this->FindVisibleQuery();

                    if ($search->getMaxPrice()) {
                        $query = $query
                            ->andWhere('p.price <= :maxprice')
                            ->setParameter('maxprice', $search->getMaxPrice());
                    }
                    if ($search->getType()) {
                        $query = $query
                            ->andWhere('p.type like :type ')
                            ->setParameter('type', '%' .$search->getType() .'%');
                    }
                    if ($search->getTitle()) {
                        $query = $query
                            ->andWhere('p.title like :title ')
                            ->setParameter('title', '%' .$search->getTitle() .'%');
                    }
                    if ($search->getColor()) {
                        $query = $query
                            ->andWhere('p.color like :color ')
                            ->setParameter('color', '%' .$search->getColor() .'%');
                    }
            
                    return $query->getQuery();
    }

       
    public function FindAllQuery(Array $panier)
    {
        $query = $this->createQueryBuilder('p');
        $i = 0;
        while ($i <= (count($panier)-1))
        {
            $query
            ->andWhere('p.id = :panier')
            ->setParameter('panier', $panier[$i]->getPlants());

            $i = $i + 1;
        }
      
        $query->getQuery()
        ->getResult(); 
    }
    
    public function deletePanierByPlants(Plants $plants)
    {
      
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\Plants', 'Plants');

     
        $sql = 'DELETE FROM `panier_plants`
        WHERE plants_id = ?
       ';
     
       
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $plants->getId());
        return $query->getResult();
     
    }

    public function FindAllByUser(Panier $panier)
    {
      
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\Plants', 'Plants');

     
        $sql = 'SELECT *, pl.qt FROM Plants P
        INNER JOIN panier_plants pl
        ON p.id=pl.plants_id
        WHERE pl.panier_id = ?
       ';
     
       
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $panier->getId());
        return $query->getResult();
     
    }

    public function findLatest() : array
    {
        return $this->createQueryBuilder('p')
        ->where('p.quantity >= 1')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult(); 
    }

    public function FindVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }
    // /**
    //  * @return Plants[] Returns an array of Plants objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plants
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
