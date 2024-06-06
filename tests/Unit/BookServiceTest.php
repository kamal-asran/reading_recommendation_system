<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\ReadingInterval;
use App\Services\BookService;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Facades\Hash;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $bookService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookService = new BookService();
        $this->seed(UsersTableSeeder::class);
    }

    protected function authenticate()
    {
        // get a user
        $user=User::first();
        // Simulate user authentication
        $this->actingAs($user);
    }

    public function test_it_calculates_unique_pages_correctly()
    {
        $this->authenticate();
        
        $book = Book::create(['name' => 'Book 1', 'num_of_pages' => 100]);
        // Create reading intervals for the book
        ReadingInterval::create(['user_id' => 1, 'book_id' => $book->id, 'start_page' => 10, 'end_page' => 30]);
        ReadingInterval::create(['user_id' => 2, 'book_id' => $book->id, 'start_page' => 15, 'end_page' => 25]);

        $topBooks = $this->bookService->getTopBooks();
        // Check that the unique read pages for the book are calculated correctly
        $this->assertEquals(21, $topBooks->first()->unique_read_pages);
    }

    public function test_it_handles_overlapping_and_non_overlapping_intervals()
    {
        $this->authenticate();

        $book = Book::create(['name' => 'Book 2', 'num_of_pages' => 200]);
        
        $user=User::first();

        // Create reading intervals for the book
        
        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 1, 'end_page' => 50]);
        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 51, 'end_page' => 100]);
        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 25, 'end_page' => 75]);

        // Get top books
        $topBooks = $this->bookService->getTopBooks();

        // Check that the unique read pages for the book are calculated correctly
        $this->assertEquals(100, $topBooks->first()->unique_read_pages);
    }
}
