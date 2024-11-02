<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    #[Test]
    public function expense_belongs_to_a_category() {
        $category = ExpenseCategory::factory()->create();
        $expense = Expense::factory()->create(['expense_category_id' => $category->id]);

        $this->assertEquals($category->id, $expense->expenseCategory->id);
    }

    #[test]
    public function authenticated_user_can_create_expense() {
        $user = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = [
            'description' => fake()->sentence(3),
            'amount' => fake()->randomFloat(2, 1, 1000),
            'date' => fake()->date(),
            'user_id' => $user->id,
            'expense_category_id' => $category->id
        ];
        $response = $this->post('/expenses',$data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expenses', ['description' => $data['description']]);
    }

    #[test]
    public function it_calculates_monthly_expenses() {
        $user = User::factory()->create();
        Expense::factory()->create(['user_id' => $user->id, 'amount' => 100, 'date' => '2024-01-01']);
        Expense::factory()->create(['user_id' => $user->id, 'amount' => 200, 'date' => '2024-02-01']);
        Expense::factory()->create(['user_id' => $user->id, 'amount' => 300, 'date' => '2024-03-01']);

        $expectedMonthlyExpenses = [
            'January 2024' => 100,
            'February 2024' => 200,
            'March 2024' => 300,
        ];

        $monthlyExpenses = $user->getMonthlyExpenses();

        $this->assertEquals($expectedMonthlyExpenses, $monthlyExpenses->toArray());
    }
}
