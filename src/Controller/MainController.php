<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Psr\Log\LoggerInterface;
use App\Service\CommentManager;
use App\Service\DeleteManager;

class MainController extends Controller
{
    /**
     * @Route("/posts", name="posts")
     */
    public function posts(Request $request, PostRepository $postRepository)
    {   
        // create pagination
        $posts = $postRepository->findAll();

        $pagination = $this->get('knp_paginator')->paginate(
            $posts,
            $request->query->getInt('page', 1),
            3
        );
        $pagination->setTemplate('@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig');

        return $this->render('main/posts.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/posts/{id}", name="showPostById", requirements={"id"="\d+"})
     */
    public function showPostById(Post $post)
    {   
        $form = $this->get(CommentManager::class)->createFormComment($post);

        return $this->render('main/post.html.twig', [
            'post' => $post,
            'form' => $form
            ]
        );
    }

    /**
     * @Route("/posts/{id}/heart", name="togglePostHeart", requirements={"id"="\d+"}, methods={"POST"})
     */ 
    public function togglePostHeart()
    {
        return $this->json(['hearts' => rand(5, 100)]);
    }

    /**
     * @Route("posts/{id}/comments/save", name="saveComment")
     */
    public function saveComment(Post $post, Request $request, LoggerInterface $logger)
    {
        $user = $this->getUser();
        $comment = $this->get(CommentManager::class)->saveComment($request, $user);

        //if comment create
        if (!$comment) {
            //show flash message
            $message = 'You add a new comment!';
            $this->addFlash('success', $message);
            //create log message
            $logger->info($message);

            return $this->redirectToRoute('showPostById', ['id' => $post->getId()]);
        }

        return $this->render('main/post.html.twig', $comment);
    }
    /**
     * @Route("posts/{id}/comments/{commentId}/delete", name="deleteComment")
     * @ParamConverter("comment", options={"mapping": {"commentId" = "id"}})
     */
    public function deleteComment(Comment $comment, Post $post, LoggerInterface $logger)
    {
        //remove comment from database
        $result = $this->get(DeleteManager::class)->deleteItemFromDB($comment);

        if ($result) {
            //show flash message
            $message = 'You delete a comment!';
            $this->addFlash('success', $message);

            //create log message
            $logger->info($message);
        }        

        return $this->redirectToRoute('showPostById', ['id' => $post->getId()] );
    }
  
}
