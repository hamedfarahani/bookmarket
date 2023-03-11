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
        $books   = Book::factory()->count(1)->make();
        $keyword = $books[0]->publisher;
        $this->elasticsearch->index([
            'index' => 'books',
            'body'  => $books[0]->toArray()
        ]);
        sleep(1);
        $response = $this->json('GET', "/api/search/book?q={$keyword}");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'publisher',
                        'title',
                        'summary',
                        'authors'
                    ]
                ]
            ]);
    }
}
