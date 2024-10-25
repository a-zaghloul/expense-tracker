<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExpenseCategoryTest extends TestCase
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
    public function category_can_have_expenses() {
        $category = ExpenseCategory::factory()->create();
        $expense = Expense::factory()->create(['expense_category_id' => $category->id]);
        $this->assertTrue($category->expenses->contains($expense));

    }
}
