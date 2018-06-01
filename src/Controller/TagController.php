<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TagController extends Controller
{
    /**
     * @Route("/tags/{tag}", name="showPostsByTag")
     * @ParamConverter("tag", options={"mapping": {"tag" = "name"}})
     */
    public function showPostsByTag(Tag $tag)
    {   
        return $this->render('tag/tag.html.twig', compact('tag'));
    }

    public function tags(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag/partial/tags.html.twig', compact('tags'));       
    }
}
