<?php

namespace Tests\Feature;

use Elastic\Elasticsearch\Client;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    protected $elasticsearch;

    public function setUp(): void
    {
        parent::setUp();

        // Set up Elasticsearch client
        $this->refreshApplication();
        $this->elasticsearch = $this->app->make(Client::class);
        if ($this->elasticsearch->indices()->exists(['index' => 'books'])->asBool()) {
            $this->elasticsearch->indices()->delete(['index' => 'books']);
        }

        // Index some test data
        $this->elasticsearch->index([
            'index' => 'books',
            'body' => [
                'id' => 1,
                'title' => 'Mastering Something',
                'publisher' => 'Packt',
                'summary' => 'This book is about mastering something.',
                'authors' => ['Author One', 'Author Two'],
            ],
        ]);

        $this->elasticsearch->index([
            'index' => 'books',
            'body' => [
                'id' => 2,
                'title' => 'Learning Laravel',
                'publisher' => 'O\'Reilly',
                'summary' => 'This book is about learning Laravel.',
                'authors' => ['Author One', 'Author Three'],
            ],
        ]);


    }

    public function testSearchReturnsMatchingBooks()
    {
        $keyword = 'Learning';
        $response = $this->get('http://localhost:8000/api/search/book?q=Learning');
        $response->assertStatus(200);


    }

    public function testSearchReturnsNoResultsForNonMatchingQuery()
    {
        $keyword = 'foobar';
        $response = $this->json('GET', "/api/search/book?q={$keyword}");

        $response->assertStatus(200);

        $response->assertJsonCount(0, 'data');
    }
}
