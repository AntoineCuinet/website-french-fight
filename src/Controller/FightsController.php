<?php

namespace App\Controller;

use App\Repository\FightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Fight;

class FightsController extends AbstractController
{
    #[Route('/combats', name: 'app_fights')]
    public function index(FightRepository $fightRepository): Response
    {
        // Separate past and upcoming fights
        $now = new \DateTime();
        
        $upcomingFights = $fightRepository->createQueryBuilder('f')
            ->where('f.date >= :now')
            ->setParameter('now', $now)
            ->orderBy('f.date', 'ASC')
            ->getQuery()
            ->getResult();

        $pastFights = $fightRepository->createQueryBuilder('f')
            ->where('f.date < :now')
            ->setParameter('now', $now)
            ->orderBy('f.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('fights/index.html.twig', [
            'upcoming_fights' => $upcomingFights,
            'past_fights' => $pastFights,
        ]);
    }

    #[Route('/combats/{id}', name: 'app_fight_show')]
    public function show(Fight $fight): Response
    {
        return $this->render('fights/show.html.twig', [
            'fight' => $fight,
        ]);
    }
}
