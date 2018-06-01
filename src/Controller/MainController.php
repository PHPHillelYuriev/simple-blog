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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Psr\Log\LoggerInterface;
// use App\Service\PostManager;

class MainController extends Controller
{
    /**
     * @Route("/posts", name="posts")
     */
    public function posts(CategoryRepository $categoryRepository)
    {   
        $categories = $categoryRepository->findAll();

        return $this->render('main/posts.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/posts/{id}", name="showPostById", requirements={"id"="\d+"})
     */
    public function showPostById(Post $post, Request $request, LoggerInterface $logger)
    {   
        $user = $this->getUser();
        $comment = new Comment($post, $user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //add new comment to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            //show flash message
            $message = 'You add a new comment!';
            $this->addFlash('success', $message);

            //create log message
            $logger->info($message);

            return $this->redirectToRoute('showPostById', ['id' => $post->getId()] );
        }

        return $this->render('main/post.html.twig', [
            'post' => $post,
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/posts/{id}/heart", name="togglePostHeart", requirements={"id"="\d+"}, methods={"POST"})
     */ 
    public function togglePostHeart()
    {
        return $this->json(['hearts' => rand(5, 100)]);
    }

    /**
     * @Route("{id}/comments/{commentId}/delete", name="deleteComment")
     * @ParamConverter("comment", options={"mapping": {"commentId" = "id"}})
     */
    public function delete(Request $request, Comment $comment, Post $post, LoggerInterface $logger)
    {
        //remove comment from database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        //show flash message
        $message = 'You delete a comment!';
        $this->addFlash('success', $message);

        //create log message
        $logger->info($message);

        return $this->redirectToRoute('showPostById', ['id' => $post->getId()] );
    }
  
}
