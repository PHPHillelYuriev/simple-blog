<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DeleteManager 
{
    private $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteItemFromDB($item)
    {
        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return true;
    }
}