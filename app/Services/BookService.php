<?php
namespace App\Services;

use App\Models\Book;
use App\Models\ReadingInterval;
use Illuminate\Support\Facades\Log;
use Exception;

class BookService
{
    public function createBook(array $data)
    {
        try {
            return Book::create($data);
        } catch (Exception $e) {
            Log::error('Error creating book: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function updateBook(Book $book, array $data)
    {
        try {
            $book->update($data);
            return $book;
        } catch (Exception $e) {
            Log::error('Error updating book: ' . $e->getMessage(), [
                'book' => $book,
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function deleteBook(Book $book)
    {
        try {
            $book->delete();
        } catch (Exception $e) {
            Log::error('Error deleting book: ' . $e->getMessage(), [
                'book' => $book,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function getAllBooks()
    {
        try {
            return Book::paginate(10);
        } catch (Exception $e) {
            Log::error('Error fetching all books: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function getBookById($id)
    {
        try {
            return Book::findOrFail($id);
        } catch (Exception $e) {
            Log::error('Error fetching book by ID: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function storeReadingInterval(array $data)
    {
        try {
            return ReadingInterval::create($data);
        } catch (Exception $e) {
            Log::error('Error storing reading interval: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function getTopBooks()
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Error fetching top books: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }

    private function calculateUniquePages($intervals)
    {
        try {
            $pages = [];
            foreach ($intervals as $interval) {
                for ($i = $interval->start_page; $i <= $interval->end_page; $i++) {
                    $pages[$i] = true;
                }
            }
            return count($pages);
        } catch (Exception $e) {
            Log::error('Error calculating unique pages: ' . $e->getMessage(), [
                'intervals' => $intervals,
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
