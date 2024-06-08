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

    protected function authenticate($isAdmin = false)
    {
        // Create a user and authenticate
        $user = User::factory()->create(['role' => $isAdmin ? 'admin':'user']);
        $this->actingAs($user);
        return $user;
    }
    public function test_only_admin_users_can_create_books()
    {
        $this->authenticate($isAdmin = false);

        $response = $this->post('/api/books', [
            'name' => 'Book 3',
            'num_of_pages' => 150
        ], ['Accept' => 'application/json']);

        $response->assertStatus(403); // Assuming a 403 Forbidden response for non-admins

        $adminUser = $this->authenticate($isAdmin = true);

        $response = $this->post('/api/books', [
            'name' => 'Book 3',
            'num_of_pages' => 150
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201); // Assuming a 201 Created response for successful creation

        // Check if the book was created in the database
        $this->assertDatabaseHas('books', [
            'name' => 'Book 3',
            'num_of_pages' => 150
        ]);
    }

    public function test_only_logged_in_users_can_submit_reading_intervals()
    {
        $book = Book::create(['name' => 'Book 4', 'num_of_pages' => 300]);

        $user = User::first();

        $response = $this->post('/api/books/reading-intervals', [
            'book_id' => $book->id,
            'start_page' => 10,
            'end_page' => 50,
            'user_id'=>$user->id

        ],['Accept' => 'application/json']);

        $response->assertStatus(401); // Assuming a 401 Unauthorized response for guests

        $user = $this->authenticate();

        $response = $this->post('/api/books/reading-intervals', [
            'book_id' => $book->id,
            'start_page' => 10,
            'end_page' => 50,
            'user_id'=>$user->id
        ], ['Accept' => 'application/json']);

        $response->assertStatus(201); // Assuming a 201 Created response for successful creation

        // Check if the reading interval was created in the database
        $this->assertDatabaseHas('reading_intervals', [
            'book_id' => $book->id,
            'start_page' => 10,
            'end_page' => 50,
            'user_id'=>$user->id
        ]);
    }

    public function test_it_calculates_unique_pages_correctly()
    {
        $user=$this->authenticate();
        
        $book = Book::create(['name' => 'Book 1', 'num_of_pages' => 100]);

        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 10, 'end_page' => 30]);
        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 15, 'end_page' => 25]);

        $topBooks = $this->bookService->getTopBooks();

        // Check that the unique read pages for the book are calculated correctly
        $this->assertEquals(21, $topBooks->first()->unique_read_pages);
    }

    public function test_it_handles_overlapping_and_non_overlapping_intervals()
    {
        $this->authenticate();

        $book = Book::create(['name' => 'Book 2', 'num_of_pages' => 200]);
        
        $user=User::first();

        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 1, 'end_page' => 50]);
        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 51, 'end_page' => 100]);
        ReadingInterval::create(['user_id' => $user->id, 'book_id' => $book->id, 'start_page' => 25, 'end_page' => 75]);

        $topBooks = $this->bookService->getTopBooks();

        // Check that the unique read pages for the book are calculated correctly
        $this->assertEquals(100, $topBooks->first()->unique_read_pages);
    }
}
