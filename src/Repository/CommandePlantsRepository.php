<?php

namespace App\Repository;

use App\Entity\CommandePlants;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CommandePlants|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandePlants|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandePlants[]    findAll()
 * @method CommandePlants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandePlantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandePlants::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CommandePlants $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function FindPlantsPDF(Int $id)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App\Entity\CommandePlants', 'CommandePlants');
      
     
        $sql = 'SELECT * FROM commande_plants cp
        WHERE cp.commande_id = ?
       ';
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        return $query->getResult();
    }
   
}
