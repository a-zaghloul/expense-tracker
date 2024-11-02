<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExpenseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }

    #[Test]
    public function it_calculates_total_expense_amount() {

        $amount = 100;
        $expense = new Expense(['amount' => $amount]);

        $this->assertEquals($amount, $expense->amount);
    }
}
