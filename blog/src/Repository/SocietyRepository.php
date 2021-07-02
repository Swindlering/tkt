<?php

namespace App\Repository;

use App\Entity\Society;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SocietyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Society::class);
    }

/**
     * Récupère une liste d'articles triés et paginés.
     *
     * @param int $page Le numéro de la page
     * @param int $nbMaxParPage Nombre maximum d'article par page     
     *
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return Paginator
     */
    //        $this->repository->findByWithPagination($search, $orderField, $order, $page, $nbMaxParPage);
    public function findByWithPagination($search = null, $orderField, $order, $page, $nbMaxParPage)
    {
        if (!is_numeric($page)) {
            throw new \Exception("Wrong page ${page}");
        }

        if ($page < 1) {
            throw new \Exception('Page NOT FOUND');
        }

        if (!is_numeric($nbMaxParPage)) {
            throw new \Exception("Wrong max per page ${nbMaxParPage}");
        }
    
        $premierResultat = ($page - 1) * $nbMaxParPage;
        $qb = $this->createQueryBuilder('s');
        
        if ($search) {
            $qb->where("s.name LIKE :search")
            ->setParameter('search', '%'.$search.'%');
        }

        $qb->orderBy("s.${orderField}", $order);
            
        $query = $qb->getQuery()
        ->setFirstResult($premierResultat)
        ->setMaxResults($nbMaxParPage);

        $societies = new Paginator($query, true);
        return [
            'data' => $societies,
            'page' => $page,
            'nbPages' => ceil(count($societies) / $nbMaxParPage)
        ];
    }
}
