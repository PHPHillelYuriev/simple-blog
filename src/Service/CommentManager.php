<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Form\CommentType;

class CommentManager
{
    private $formFactory;
    private $entityManager;

    public function __construct(
        FormFactoryInterface $formFactory, 
        EntityManagerInterface $entityManager
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function createComment(Request $request, $post, string $user = null)
    {
        $comment = new Comment($post, $user);
        $form = $this->formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        
            return;
        }

        return [
            'post' => $post,
            'comment' => $comment,
            'form' => $form->createView(),
        ];  
    }
}