<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Services\BookService;
use App\Http\Resources\BookResource;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        $books = $this->bookService->getAllBooks();
        return BookResource::collection($books);
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->createBook($request->validated());
        return response()->json(['message'=>'saved successfully'], 201);
    }

    public function show($id)
    {
        $book = $this->bookService->getBookById($id);
        return new BookResource($book);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $book = $this->bookService->getBookById($id);
        $book = $this->bookService->updateBook($book, $request->validated());
        return response()->json(['message'=>'updated successfully']);
    }

    public function destroy($id)
    {
        $book = $this->bookService->getBookById($id);
        $this->bookService->deleteBook($book);
        return response()->json(null, 204);
    }
}
