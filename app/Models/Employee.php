<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'job_title',
        'salary',
    ];

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function scopeSortBy(Builder $query, string $sortBy = 'name', string $direction = 'asc'): Builder
    {
        return $query->orderBy($sortBy, $direction);
    }
}
