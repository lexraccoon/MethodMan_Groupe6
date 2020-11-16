<?php

namespace App\Repository;

use App\Entity\HTTPRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HTTPRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method HTTPRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method HTTPRequest[]    findAll()
 * @method HTTPRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HTTPRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HTTPRequest::class);
    }

    /* requête qui selectionne toutes les données des requêtes dans la bdd*/

    public function getAllHTTPRequest(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT h.id, h.method, h.url, h.token
            FROM App\Entity\HTTPRequest h'
        );

        return $query->getResult();
    }

    /* fonction qui supprime dans la bdd la requête choisi en fonction du id
    probleme dans l'utilisation de render(controller('App\Controller\HomeController::getDeleteHTTPRequest',{ 'id': query.id }))*/

    /*public function deleteHTTPRequest($id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'DELETE App\Entity\HTTPRequest
            WHERE id = :id'
        );

        $query->setParameter('id', $id);

        return $query;
    }*/

    // /**
    //  * @return Requetes[] Returns an array of Requetes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Requetes
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
