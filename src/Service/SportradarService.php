<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SportradarService
{
    private const BASE_URL = 'https://api.sportradar.com/mma/trial/v2/en';

    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'SPORTRADAR_API_KEY')]
        private string $apiKey
    ) {
    }

    public function getSchedule(): array
    {
        // Endpoint pour le calendrier : /schedule.json (Global Schedule)
        // Documentation: https://developer.sportradar.com/mma/reference/mma-overview
        try {
            $response = $this->client->request('GET', self::BASE_URL . '/schedule.json', [
                'query' => [
                    'api_key' => $this->apiKey,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }
        } catch (\Exception $e) {
            // Si l'API retourne 403 (clé invalide) ou 404, on utilise le Mock
        }

        // MOCK DATA (Fallback si l'API échoue ou clé invalide)
        return [
            'schedules' => [
                [
                    'id' => 'mock_1',
                    'scheduled' => (new \DateTime('+1 month'))->format('c'),
                    'category' => ['name' => 'UFC 300'],
                    'venue' => ['city_name' => 'Las Vegas', 'country_name' => 'USA'],
                    'competitors' => [
                        ['name' => 'Conor McGregor'],
                        ['name' => 'Michael Chandler']
                    ]
                ],
                [
                    'id' => 'mock_2',
                    'scheduled' => (new \DateTime('+2 weeks'))->format('c'),
                    'category' => ['name' => 'PFL Paris'],
                    'venue' => ['city_name' => 'Paris', 'country_name' => 'France'],
                    'competitors' => [
                        ['name' => 'Cédric Doumbé'],
                        ['name' => 'Baki Chamsoudinov']
                    ]
                ],
                [
                    'id' => 'mock_3',
                    'scheduled' => (new \DateTime('+3 days'))->format('c'),
                    'category' => ['name' => 'Bellator 300'],
                    'venue' => ['city_name' => 'London', 'country_name' => 'UK'],
                    'competitors' => [
                        ['name' => 'Usman Nurmagomedov'],
                        ['name' => 'Alexander Shabliy']
                    ]
                ],
                [
                    'id' => 'mock_4',
                    'scheduled' => (new \DateTime('-1 day'))->format('c'),
                    'category' => ['name' => 'UFC Fight Night'],
                    'venue' => ['city_name' => 'Abu Dhabi', 'country_name' => 'UAE'],
                    'competitors' => [
                        ['name' => 'Islam Makhachev'],
                        ['name' => 'Charles Oliveira']
                    ]
                ]
            ]
        ];
    }
}
