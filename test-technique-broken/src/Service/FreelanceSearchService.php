<?php

namespace App\Service;

use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;
use App\Entity\Freelance;
use Psr\Log\LoggerInterface;

readonly class FreelanceSearchService
{
    private Client $elasticsearchClient;

    public function __construct(
        #[Autowire(service: "fos_elastica.finder.freelance")]
        private PaginatedFinderInterface $freelanceFinder,
        private LoggerInterface $logger
    ) {
        $this->elasticsearchClient = ClientBuilder::create()
            ->setHosts(['test-technique-broken-elasticsearch-1:9200'])  // Nom exact du conteneur
            ->setRetries(2)  // Ajout de tentatives de reconnexion
            ->setConnectionParams([
                'client' => [
                    'curl' => [
                        CURLOPT_TIMEOUT => 5,
                        CURLOPT_CONNECTTIMEOUT => 5
                    ]
                ]
            ])
            ->build();
    }


    // public function indexFreelance(Freelance $freelance): void
    // {
    //     $freelanceConso = $freelance->getFreelanceConso();
    //     $document = [
    //         'id' => $freelance->getId(),
    //         'firstName' => $freelanceConso?->getFirstName(),
    //         'lastName' => $freelanceConso?->getLastName(),
    //         'jobTitle' => $freelanceConso?->getJobTitle()
    //     ];

    //     $this->elasticsearchClient->index([
    //         'index' => 'freelances',
    //         'id' => $freelance->getId(),
    //         'body' => $document
    //     ]);
    // }

    public function searchFreelance(string $query): array
    {
        try {
            // Vérifier si Elasticsearch est accessible
            //dd($this->elasticsearchClient->cluster()->health());
            $health = $this->elasticsearchClient->cluster()->health();
            dd([
                'health' => $health,
                'host' => 'test-technique-broken-elasticsearch-1:9200'
            ]);
            $params = [
                'index' => 'freelances',
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $query,
                            'fields' => ['firstName', 'lastName', 'jobTitle'],
                            'fuzziness' => 'AUTO'
                        ]
                    ]
                ]
            ];

            $results = $this->elasticsearchClient->search($params);
            return array_map(function ($hit) {
                return $hit['_source'];
            }, $results['hits']['hits']);
        } catch (\Exception $e) {
            //$this->logger->error('Elasticsearch error: ' . $e->getMessage());
            //dd($e->getMessage());
            //return [];
            dd([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'host' => 'test-technique-broken-elasticsearch-1:9200'
            ]);
        }
    }
}
