<?php

namespace App\Console\Commands;

use App\Models\Book;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;


class StoreElasticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'store 1 million books in elastic';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $client = App::make(Client::class);


        $chunkSize   = 5000; // number of records to insert per chunk
        $totalChunks = 200; // total number of chunks to insert

        for ($i = 1; $i <= $totalChunks; $i++) {
            $books = Book::factory()->count($chunkSize)->make();

            $params = ['body' => []];

            foreach ($books as $book) {
                $params['body'][] = [
                    'index' => [
                        '_index' => 'books',
                        '_id'    => $book->id,
                    ],
                ];
                $params['body'][] = $book->toArray();
            }

            $client->bulk($params);

            $this->info("Inserted chunk $i of $totalChunks");
        }

        $this->info('Inserted 1 million books into Elasticsearch');
    }
}
