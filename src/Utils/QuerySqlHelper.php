<?php

namespace App\Utils;

use App\Entity\CardSuit;
use App\Entity\CardValue;
use Doctrine\ORM\EntityManagerInterface;

class QuerySqlHelper
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function endPointQuery(string $keySqlMethod, array $params)
    {
        return $this->$keySqlMethod($params);
    }


    /* ** ********************* ** *  QUERY SQL * ** ********************* ** */

    private function getAllCardsSuit(array $params)
    {
        return $this->entityManager->getRepository(CardSuit::class)->findAll();
    }

    private function getAllCardsValue(array $params)
    {
        return $this->entityManager->getRepository(CardValue::class)->findAll();
    }

    /* ** ********************* ** *  QUERY SQL * ** ********************* ** */

}
