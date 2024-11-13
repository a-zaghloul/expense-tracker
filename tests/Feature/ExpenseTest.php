<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function expense_belongs_to_a_category() {
        $category = ExpenseCategory::factory()->create();
        $expense = Expense::factory()->create(['expense_category_id' => $category->id]);

        $this->assertEquals($category->id, $expense->expenseCategory->id);
    }

    #[Test]
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

    #[Test]
    public function authenticated_user_can_edit_expense() {
        $user = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);
        $expense = Expense::factory()->create(['user_id' => $user->id, 'expense_category_id' => $category->id]);

        $this->actingAs($user);

        $data = [
            'description' => fake()->sentence(3),
            'amount' => fake()->randomFloat(2, 1, 1000),
            'date' => fake()->date(),
            'user_id' => $user->id,
            'expense_category_id' => $category->id
        ];

        $response = $this->put("/expenses/$expense->id", $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expenses', [
            'description' => $data['description'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'user_id' => $data['user_id'],
            'expense_category_id' => $data['expense_category_id']
        ]);
    }

    #[Test]
    public function authenticated_user_can_delete_expense() {
        $user = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);
        $expense = Expense::factory()->create(['user_id' => $user->id, 'expense_category_id' => $category->id]);

        $this->actingAs($user);

        $response = $this->delete("/expenses/$expense->id");

        $response->assertStatus(302);
        !$this->assertDatabaseHas('expenses', ['id' => $expense->id]);
    }

    #[Test]
    public function authenticated_user_can_view_his_list_of_expenses() {
        $user = User::factory()->create();
        $expenses = Expense::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get("/expenses");

        $response->assertOk();
        $response->assertViewIs("expenses.index");
        $response->assertViewHas('expenses', function($viewExpenses) use ($expenses){
            return $viewExpenses->pluck('id')->diff($expenses->pluck('id'))->isEmpty();
        });
    }

    #[Test]
    public function it_fails_to_create_expense_if_wrong_fields_values_provided() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $url = '/expenses';

        $response = $this->post($url, []);

        $response->assertSessionHasErrors(['description', 'amount', 'date', 'expense_category_id']);

        $data = [
            'description' => '',
            'amount' => -2,
            'date' => 'not a date',
            'expense_category_id' => 'not an id'
        ];
        $response = $this->post('/expenses',$data);

        $response->assertSessionHasErrors([
            'description' => 'The description field is required.',
            'amount' => 'The amount field must be at least 1.',
            'date' => 'The date field must be a valid date.',
            'expense_category_id' => 'The selected expense category id is invalid.'
        ]);
    }

    #[Test]
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
