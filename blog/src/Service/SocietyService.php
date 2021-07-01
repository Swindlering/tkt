<?php

namespace App\Service;

use App\Entity\Society;
use App\Entity\Resultat;
use App\Entity\User;
use App\Repository\SocietyRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class SocietyService
{
    private $em;
    private $repository;

    /**
     * __construct
     */
    public function __construct(EntityManagerInterface $em, SocietyRepository $societyRepository)
    {
        $this->em = $em;
        $this->repository = $societyRepository;
    }

    /**
     * Get all Society and format it
     * @return array
     */
    public function getList($search = [], $orderField = 'name', $order = 'ASC', $offset = 0, $limit = 20): array
    {
        // TO DO Change findBy to the normal sql 
        // TO DO use Doctrine\ORM\Tools\Pagination\Paginator to manage pagination

        return array_map(
            function (Society $society) {
                return [
                    'id' => $society->getId(),
                    'name' => $society->getName(),
                    'siren' => $society->getSiren(),
                    'sector' => $society->getSector(),
                ];
            },
            $this->repository->findBy($search, ["$orderField" => "$order"], $limit, 0)
        );
    }
    /**
     * format the society results
     */
    private function formatResult($resultats): array
    {
        $format = [];
        // TO DO refactor this ugly code
        foreach ($resultats as $key => $resultat) {
            $keyCompare = ($key === 0) ? 1 : $key - 1;
            $format[$resultat->getYear()] = [
                'id' => $resultat->getId(),
                'ca' => [
                    'value' => $resultat->getCa(),
                    'color' => ($resultats[$key]->getCa() < $resultats[$keyCompare]->getCa()) ? 'red' : 'green'
                    ],
                'margin' => [
                    'value' => $resultat->getMargin(),
                    'color' => ($resultats[$key]->getMargin() < $resultats[$keyCompare]->getMargin()) ? 'red' : 'green'
                    ],
                'ebitda' => [
                    'value' => $resultat->getEbitda(),
                    'color' => ($resultats[$key]->getEbitda() < $resultats[$keyCompare]->getEbitda()) ? 'red' : 'green'
                    ],
                'loss' => [
                    'value' => $resultat->getLoss(),
                    'color' => ($resultats[$key]->getLoss() < $resultats[$keyCompare]->getLoss()) ? 'red' : 'green'
                    ],
                'year' => $resultat->getYear(),
            ];
        }

        return $format;

        // return array_map(
        //     function (Resultat $resultat) {
        //         return [
        //             'id' => $resultat->getId(),
        //             'ca' => $resultat->getCa(),
        //             'margin' => $resultat->getMargin(),
        //             'ebitda' => $resultat->getEbitda(),
        //             'loss' => $resultat->getLoss(),
        //             'year' =>$resultat->getYear()
        //         ];
        //     },
        //     $resultats
        // );
    }
    /**
     * get one society and return it
     * @parm id
     * @return array
     */
    public function getOneById(int $id): ?array
    {
        $society =  $this->repository->findOneById($id);
        // manage if it not exist
        if (!$society) {
            return [];
        }
        return [
            'id' => $society->getId(),
            'name' => $society->getName(),
            'siren' => $society->getSiren(),
            'sector' => $society->getSector(),
            'results' => $this->formatResult($society->getResults()->getValues())
        ];
    }
}
