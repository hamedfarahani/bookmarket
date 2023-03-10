<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use App\Services\Interfaces\BookServiceInterface;

class BookController extends Controller
{
    public function __construct(private BookServiceInterface $bookService)
    {
    }

    public function search(Request $request)
    {
        $books = $this->bookService->search($request->input('q'));

        return BookResource::collection($books);
    }
}
