<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->input('name');

        $user = $request->user();

        $categories = $user->categories();

        if ($name) {
            $categories->where('name', 'like', "%".$name."%");
        }

        $categories = $categories->paginate(5);
        return view('expensecategories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expensecategories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);

        $user->categories()->create($validated);

        return redirect('/expensecategories');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);
        Gate::authorize('update-expense-category', $expenseCategory);
        return view('expensecategories.edit', compact("expenseCategory"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, Request $request)
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);
        Gate::authorize('update-expense-category', $expenseCategory);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);

        $expenseCategory->update($validated);

        return redirect('/expensecategories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);
        Gate::authorize('update-expense-category', $expenseCategory);
        $expenseCategory->delete();
        return redirect('/expensecategories');
    }


}
