<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
