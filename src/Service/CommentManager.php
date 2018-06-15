<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Entity\Post;
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

    public function saveCommentToDb(Request $request, Post $post, $user = null)
    {
        $comment = new Comment($post, $user);
        $form = $this->formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();    
        
            return;
        } 
    }

    // public function saveComment(Request $request, string $user)
    // {   
    //     $comment = new Comment();
    //     $commentFromRequest = $request->get('comment');
    //     $comment->setContent($commentFromRequest['content']);
    //     $comment->setPost($request->get('post'));
    //     $comment->setAuthor($user);
        
    //     $this->entityManager->persist($comment);
    //     $this->entityManager->flush();
        
    //     return;  
    // }

    public function createFormComment(Post $post)
    {
        $comment = new Comment($post);
        $form = $this->formFactory->create(CommentType::class, $comment);

        return $form->createView();
    }
}