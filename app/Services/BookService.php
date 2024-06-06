<?php
namespace App\Services;

use App\Models\Book;
use App\Models\ReadingInterval;

class BookService
{
 
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
    public function storeReadingInterval($data)
    {
        return ReadingInterval::create($data);
    }

    public function getTopBooks()
    {
        $books = Book::with('readingIntervals')
            ->get()
            ->map(function ($book) {
                $uniquePages = $this->calculateUniquePages($book->readingIntervals);
                $book->unique_read_pages = $uniquePages;
                return $book;
            })
            ->sortByDesc('unique_read_pages')
            ->take(5);

        return $books;
    }

    private function calculateUniquePages($intervals)
    {
        $pages = [];
        foreach ($intervals as $interval) {
            for ($i = $interval->start_page; $i <= $interval->end_page; $i++) {
                $pages[$i] = true;
            }
        }
        return count($pages);
    }
}
