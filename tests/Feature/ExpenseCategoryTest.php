<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExpenseCategoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function category_can_have_expenses() {
        $category = ExpenseCategory::factory()->create();
        $expense = Expense::factory()->create(['expense_category_id' => $category->id]);
        $this->assertTrue($category->expenses->contains($expense));

    }

    #[Test]
    public function authenticated_user_can_create_expense_category() {
        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => fake()->word(),
            'user_id' => $user->id,
        ];
        $response = $this->post('/expensecategories',$data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expense_categories', ['name' => $data['name']]);
    }

    #[Test]
    public function authenticated_user_can_edit_expense() {
        $user = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = [
            'name' => fake()->word(),
            'user_id' => $user->id,
        ];

        $response = $this->put("/expensecategories/$category->id", $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expense_categories', [
            'name' => $data['name'],
            'user_id' => $data['user_id'],
        ]);
    }

    #[Test]
    public function authenticated_user_can_delete_expense() {
        $user = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->delete("/expensecategories/$category->id");

        $response->assertStatus(302);
        !$this->assertDatabaseHas('expense_categories', ['id' => $category->id]);
    }

    #[Test]
    public function authenticated_user_can_view_his_list_of_expenses() {
        $user = User::factory()->create();
        $categories = ExpenseCategory::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get("/expensecategories");

        $response->assertOk();
        $response->assertViewIs("expensecategories.index");
        $response->assertViewHas('categories', function($viewCategories) use ($categories){
            return $viewCategories->pluck('id')->diff($categories->pluck('id'))->isEmpty();
        });
    }
}
