<?php

namespace App\Service;

use Elasticsearch\ClientBuilder;


class NewsGenerator
{
    public function getElasticConnection()
    {
        $hosts = [
            '127.0.0.1:9200'
        ];

        return ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($hosts)      // Set the hosts
        ->build();
    }


    public function createNewsIndex()
    {
        $client = $this->getElasticConnection();

        $params = [
            'index' => 'news',
            'body' => [
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'content' => [
                            'type' => 'text'
                        ],
                        'description' => [
                            'type' => 'text'
                        ],
                        'name' => [
                            'type' => 'text'
                        ],
                        'author' => [
                            'type' => 'text'
                        ]
                    ]
                ]
            ]
        ];

        $client->indices()->create($params);
    }


    public function fillNews($news)
    {
        $client = $this->getElasticConnection();

        $params = ['body' => []];

        foreach ($news as $novelty) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'news',
                    '_id' => $novelty->getId()
                ]
            ];

            $params['body'][] = [
                'content' => $novelty->getContent(),
                'description' => $novelty->getDescription(),
                'name' => $novelty->getName(),
                'author' => $novelty->getAuthor(),
            ];
        }

        if (!empty($params['body'])) {
            $client->bulk($params);
        }
    }

    public function searchNews($keyword){

        $client = $this->getElasticConnection();

        $params = [
            'index' => 'news',
            'body' => [
                "query" => [
                    "multi_match" => [
                        "query" => $keyword,
                        "fields" => ["name", "description", "content", "author"]
                    ]
                ]
            ]
        ];

        $response = [];

        $results = $client->search($params);
        foreach ($results['hits']['hits'] as $result) {
            $response[] = [
                'description' => substr($result['_source']['description'], 0, 100),
                'name' => $result['_source']['name'],
                'author' => $result['_source']['author'],
                'id' => $result['_id']
            ];
        }
        return $response;
    }

    public function updateNews($novelty){

        $client = $this->getElasticConnection();

            $params = [
                'index' => 'news',
                'id' => $novelty->getId(),
                'body' => [
                    'doc' => [
                        'content' => $novelty->getContent(),
                        'description' => $novelty->getDescription(),
                        'name' => $novelty->getName(),
                        'author' => $novelty->getAuthor()
                    ]
                ]
            ];


       $client->update($params);
    }

    public function deleteNews($novelty){

        $client = $this->getElasticConnection();

            $params = [
                'index' => 'news',
                'id' => $novelty->getId()
            ];

        $client->delete($params);
    }

    public function newNovelty($novelty){

        $client = $this->getElasticConnection();

        $params = [
            'index' => 'news',
            'id' => $novelty->getId(),
            'body' => [
                'doc' => [
                    'content' => $novelty->getContent(),
                    'description' => $novelty->getDescription(),
                    'name' => $novelty->getName(),
                    'author' => $novelty->getAuthor()
                ]
            ]
        ];

        $client->create($params);
    }
}