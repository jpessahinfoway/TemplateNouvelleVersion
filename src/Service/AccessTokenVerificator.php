<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class AccessTokenVerificator
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isAccessTokenValid(string $token, string $username)
    {

    }

}