<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getMonthlyExpenses() {
        $monthlyExpenses = $this->expenses->groupBy(function($date) {
            return Carbon::parse($date->date)->format('Y-m'); // Group by year and month
        })->map(function ($row) {
            return $row->sum('amount'); // Sum the amount for each group
        });
        $sortedMonthlyExpenses = $monthlyExpenses->sortKeys();
        $formattedMonthlyExpenses = $sortedMonthlyExpenses->mapWithKeys(function($value, $key) {
            return [Carbon::createFromFormat('Y-m', $key)->format('F Y') => $value];
        });
        return $formattedMonthlyExpenses;
    }
}
