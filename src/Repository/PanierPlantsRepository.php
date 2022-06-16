<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Plants;
use Doctrine\ORM\Query;
use App\Entity\PanierPlants;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method PanierPlants|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierPlants|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierPlants[]    findAll()
 * @method PanierPlants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierPlantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierPlants::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PanierPlants $entity, bool $flush = true): void
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
    public function remove(PanierPlants $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeByPlants(Int $id): void
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\Plants', 'Plants');
     
        $sql = 'DELETE FROM `panier_plants`
        WHERE plants_id = ?
       ';
     
       
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->execute();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeByUser(User $user): void
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\Plants', 'Plants');
     
        $sql = 'DELETE FROM `panier_plants`
        WHERE panier_id = ?
       ';
     
       
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getPanier());
        $query->execute();
    }

    public function FindByPanier(Panier $panier)
    {
      
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\PanierPlants', 'PanierPlants');
     
        $sql = 'SELECT * FROM panier_plants pl
        WHERE pl.panier_id = ?
       ';
     
       
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $panier->getId());
        return $query->getResult();
     
    }

    /**
     * Undocumented function
     *
     * @param Panier $panier
     * @param Plants $plants
     */
    public function FindExistPlant(Panier $panier, Plants $plants)
    {
      
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\PanierPlants', 'PanierPlants');
     
        $sql = 'SELECT count(*) FROM panier_plants pl
        WHERE pl.panier_id = ? 
       ';
     
       
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $panier->getId());
    
        return $query->getResult();
     
    }

    /**
    * @return PanierPlants[] Returns an array of PanierPlants objects
    */
    public function FindByPlants(Plants $Plants, Panier $Panier)
    {
      
        return $this->createQueryBuilder('p')
                    ->andWhere('p.Plants = :Plants')
                    ->andWhere('p.Panier = :Panier')
                    ->setParameter('Plants', $Plants)
                    ->setParameter('Panier', $Panier)
                    ->getQuery()
                    ->getResult(); 
    ;
     
    }


    // /**
    //  * @return PanierPlants[] Returns an array of PanierPlants objects
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
    public function findOneBySomeField($value): ?PanierPlants
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
