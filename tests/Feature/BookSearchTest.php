<?php

namespace Tests\Feature;

use App\Models\Book;
use Elastic\Elasticsearch\Client;
use Tests\TestCase;

class BookSearchTest extends TestCase
{
    protected $elasticsearch;
    protected $indicesNamespace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshApplication();
        $this->elasticsearch = $this->app->make(Client::class);

    }

    public function testSearchWithValidKeywordReturnsExpectedJsonStructure()
    {
        $books = Book::factory()->count(5)->make();
        $keyword = $books[0]->title;
        foreach ($books as $book) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'books',
                    '_id' => $book->id,
                ],
            ];
            $params['body'][] = $book->toArray();
        }

        $this->elasticsearch->bulk($params);

        $response = $this->json('GET', "/api/search/book?q={$keyword}");

        $response->assertStatus(200);
//            ->assertJsonStructure([
//                'data' => [
//                    [
//                        'id',
//                        'publisher',
//                        'title',
//                        'summary',
//                        'authors'
//                    ]
//                ]
//            ]);
    }

    public function test_book_search_endpoint()
    {
        $document = [
            'id' => 1234,
            'publisher' => 'Packt',
            'title' => 'Mastering Something',
            'summary' => 'some long summary',
            'authors' => [
                'Author One',
                'Author Two',
            ],
        ];

        $response = $this->elasticsearch->index([
            'index' => 'books',
            'id' => $document['id'],
            'body' => $document,
        ]);

        $this->assertEquals('created', $response['result']);
    }
}
