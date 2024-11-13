<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenseCategory;
use Database\Factories\UserFactory;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    // public function test_user_can_register_with_valid_credentials () {
    //     $response = $this->post('/register', [
    //         'name' => 'John Doe',
    //         'email' => 'johndoe@email.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //     ]);

    //     $response->assertRedirect('/home');

    //     $this->assertDatabaseHas('users', ['email', 'johndoe@email.com']);

    //     $this->assertAuthenticated();
    // }

    // public function test_user_can_login_with_valid_credentials()
    // {

    //     $email = 'schuppe.cleta@example.net';
    //     $password = 'password';

    //     $user = User::where('email', $email)->first();

    //     $response = $this->post('/login', [
    //         'email' => $email,
    //         'password' => $password,
    //     ]);

    //     $response->assertRedirect('/home');
    //     $this->assertAuthenticatedAs($user);
    // }

    #[Test]
    public function user_can_have_categories()
    {
        $user = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->categories->contains($category));
    }

}
