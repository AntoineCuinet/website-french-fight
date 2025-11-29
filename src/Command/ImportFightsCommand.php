<?php

namespace App\Command;

use App\Entity\Fight;
use App\Repository\FightRepository;
use App\Service\SportradarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-fights',
    description: 'Importe les combats depuis l\'API Sportradar',
)]
class ImportFightsCommand extends Command
{
    public function __construct(
        private SportradarService $sportradarService,
        private EntityManagerInterface $entityManager,
        private FightRepository $fightRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importation des combats depuis Sportradar...');

        $data = $this->sportradarService->getSchedule();

        if (empty($data) || !isset($data['schedules'])) {
            $io->error('Aucune donnée récupérée ou format invalide.');
            return Command::FAILURE;
        }

        $count = 0;

        foreach ($data['schedules'] as $event) {
            // On s'intéresse aux événements qui ont des compétiteurs définis
            if (!isset($event['competitors']) || count($event['competitors']) < 2) {
                continue;
            }

            // Extraction des données de base
            $fighterA_Name = $event['competitors'][0]['name'];
            $fighterB_Name = $event['competitors'][1]['name'];
            $dateStr = $event['scheduled']; // Format ISO 8601
            $date = new \DateTime($dateStr);
            
            // Gestion du Lieu
            $location = 'Inconnu';
            if (isset($event['venue']['city_name'])) {
                $location = $event['venue']['city_name'];
                if (isset($event['venue']['country_name'])) {
                    $location .= ', ' . $event['venue']['country_name'];
                }
            }

            // Type d'événement et Nom
            $eventName = $event['category']['name'] ?? 'Unknown Event';
            $type = 'MMA'; // Default type

            // Dédoublonnage : On vérifie si un combat existe déjà pour ces deux combattants à cette date (à peu près)
            // On cherche une fourchette de 24h pour éviter les problèmes de fuseaux horaires exacts
            $start = (clone $date)->modify('-12 hours');
            $end = (clone $date)->modify('+12 hours');

            $existingFight = $this->fightRepository->createQueryBuilder('f')
                ->where('f.fighterA = :a AND f.fighterB = :b AND f.date BETWEEN :start AND :end')
                ->orWhere('f.fighterA = :b AND f.fighterB = :a AND f.date BETWEEN :start AND :end')
                ->setParameter('a', $fighterA_Name)
                ->setParameter('b', $fighterB_Name)
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getQuery()
                ->getOneOrNullResult();

            if ($existingFight) {
                $io->note("Combat existant : $fighterA_Name vs $fighterB_Name (Ignoré)");
                continue;
            }

            // Création du combat
            $fight = new Fight();
            $fight->setFighterA($fighterA_Name);
            $fight->setFighterB($fighterB_Name);
            $fight->setDate($date);
            $fight->setLocation($location);
            $fight->setType($type);
            $fight->setEventName($eventName);
            
            // Mock data enrichment for new fields
            $fight->setWeightClass('Lightweight');
            $fight->setRounds(3);
            $fight->setIsTitleFight(false);
            $fight->setStatus('scheduled');
            
            $this->entityManager->persist($fight);
            $count++;
        }

        $this->entityManager->flush();

        $io->success("$count nouveaux combats importés avec succès !");

        return Command::SUCCESS;
    }
}
