<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->input('sortBy', 'date');  // Default sorting by date
        $direction = $request->input('direction', 'desc');  // Default descending order
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = $request->user();
        $expenses = $user->expenses()->orderBy($sortBy, $direction);

        if ($startDate) {
            $expenses->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $expenses->whereDate('date', '<=', $endDate);
        }
        $expenses = $expenses->paginate(10);

        return view('expenses.index', compact('expenses', 'sortBy', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $categories = $user->categories()->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $user = $request->user();
        $user->expenses()->create($request->input());
        return redirect('/expenses');
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
    public function edit(Expense $expense, Request $request)
    {
        Gate::authorize('update', $expense);
        $user = $request->user();
        $categories = $user->categories()->get();
        return view('expenses.edit', compact("expense", "categories"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Expense $expense, ExpenseRequest $request)
    {
        Gate::authorize('update', $expense);
        $expense->update($request->input());
        return redirect('/expenses');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        Gate::authorize('update', $expense);
        $expense->delete();
        return redirect('/expenses');

    }
}
