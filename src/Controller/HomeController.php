<?php

namespace App\Controller;

use App\Repository\BlogPostRepository;
use App\Repository\FightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(FightRepository $fightRepository, BlogPostRepository $blogPostRepository): Response
    {
        // Fetch latest 3 fights for the hero/featured section
        $latestFights = $fightRepository->findBy([], ['date' => 'DESC'], 3);

        // Fetch latest 6 blog posts
        $latestBlogs = $blogPostRepository->findBy([], ['publishedAt' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'latest_fights' => $latestFights,
            'latest_blogs' => $latestBlogs,
        ]);
    }
}
