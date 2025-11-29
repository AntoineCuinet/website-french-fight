<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        $posts = $blogPostRepository->findBy([], ['publishedAt' => 'DESC']);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/{id}', name: 'app_blog_show')]
    public function show(BlogPost $post): Response
    {
        return $this->render('blog/show.html.twig', [
            'post' => $post,
        ]);
    }
}
