<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationManager
{
    
    private $paginator;
    private $entityManager;

    public function __construct(
        PaginatorInterface $paginator, 
        EntityManagerInterface $entityManager
    ) {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    public function createPagination(Request $request, string $dql, int $countItems)
    {
        $query = $this->entityManager->createQuery($dql);

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $countItems
        );
        $pagination->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');

        return ['pagination' => $pagination];
    }
}