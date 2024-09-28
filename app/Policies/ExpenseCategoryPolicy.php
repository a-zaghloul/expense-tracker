<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;

class ExpenseCategoryPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function updateExpenseCategory(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $expenseCategory->user->is($user);
    }
}
