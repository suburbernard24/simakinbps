<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendQuote extends Model
{
    protected $table = 'frontend_quotes';

    protected $fillable = [
        'nama',
        'jabatan',
        'quote',
        'foto',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjXZA9MLptXHc4LghoJ0kijRke5AbNhNWkkCx1xTrmprh9StVUt9Q6xOhnsMWvUhNjiGK5UxXHLEzMnmMNm6BQlVWJMmdQAhyYP_Fvo03iCpoUgxxlDCiGxWpMuQOG98pXOD1nugLcPIzPemY1fQGA_K-0F0BbNO3UKS83gx_AfzVDR8tbvD5wZTd3hEtfzEOycA4oWrh3rTW5vJRAm1IQtTnDkXZTFFIxOfNJrlLf8ikRWaLhNCqN';
    }
}
