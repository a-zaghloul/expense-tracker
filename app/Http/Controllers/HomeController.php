<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\ExpensesChart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $monthlyExpenses = $user->getMonthlyExpenses();
        $linechart = $this->getLineChart($monthlyExpenses, 'Monthly Expenses');
        $piechart = $this->getPieChart($user);

        return view('home', compact('linechart', 'piechart'));
    }

    private function getLineChart($data, $title) {
        $labels = $data->keys();
        $values = $data->values();
        $linechart = new ExpensesChart;
        $linechart->labels($labels);
        $linechart->dataset($title, 'line', $values)
                ->options([
                    'fill' => true,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgb(180, 180, 180, 0.5)',
                    'pointBackgroundColor' => 'rgb(255, 255, 255)',
                    'pointBorderColor' => 'rgb(200, 0, 0)',
                    'pointBorderWidth' => 2,
                    'borderWidth' => 4,
                ]);
        return $linechart;
    }

    private function getPieChart($user) {

        $expensesPerCategories = $user->categories()
                                    ->select('id', 'name')
                                    ->withSum('expenses', 'amount') // Sum the 'amount' in the 'expenses' relation
                                    ->get()
                                    ->pluck('expenses_sum_amount', 'name');

        $labels = $expensesPerCategories->keys();
        $values = $expensesPerCategories->values();

        $generateRandomColor = function () {
            // return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            // return 'rgb(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.5)';
            return 'rgb(' . rand(50, 100) . ', ' . rand(50, 100) . ', ' . rand(50, 100) . ', 0.5)';
        };

        // Generate background colors dynamically using the collection map method
        $backgroundColors = $values->map(function () use ($generateRandomColor) {
            return $generateRandomColor();
        });

        $piechart = new ExpensesChart;
        $piechart->labels($labels);
        $piechart->dataset('Expense Categories', 'pie', $values)
                ->backgroundColor($backgroundColors)
                ->options([
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'borderColor' => 'rgb(0, 0, 0)',
                    'borderWidth' => 0.3,
                    'weight' => 5
                ]);
        return $piechart;
    }
}
