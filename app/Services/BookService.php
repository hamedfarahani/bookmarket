<?php


namespace App\Services;


use App\Services\Interfaces\BookServiceInterface;
use Elastic\Elasticsearch\Client;
use Throwable;

class BookService implements BookServiceInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search($keyword)
    {
        try {
            $params = [
                'index' => 'books',
                'body'  => [
                    'query'   => [
                        'multi_match' => [
                            'query'  => $keyword,
                            'fields' => ['title', 'summary', 'publisher', 'authors'],
                        ],
                    ],
                    '_source' => ['id', 'title', 'summary', 'publisher', 'authors']
                ],
            ];

            $response = $this->client->search($params);
            $books    = collect($response['hits']['hits'])->pluck('_source')->all();

            return $books;
        } catch (Throwable $throwable) {

        }

    }
}
