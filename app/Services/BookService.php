<?php
namespace App\Services;

use App\Models\Book;

class BookService
{
    public function getTopBooks()
    {
        return Book::withSum('readingIntervals', 'end_page - start_page + 1 as read_pages')
            ->orderByDesc('read_pages')
            ->take(5)
            ->get();
    }

    public function createBook(array $data)
    {
        return Book::create($data);
    }

    public function updateBook(Book $book, array $data)
    {
        $book->update($data);
        return $book;
    }

    public function deleteBook(Book $book)
    {
        $book->delete();
    }

    public function getAllBooks()
    {
        return Book::paginate(10);
    }

    public function getBookById($id)
    {
        return Book::findOrFail($id);
    }
}
