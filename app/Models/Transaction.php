<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "payment_method",
        "blocked"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transactionProduct(){
        return $this->hasMany(TransactionProduct::class);
    }

    const PAYMENT_METHODS = [
        'CREDIT' => 'CREDIT',
        'DEBIT' => 'DEBIT',
        'MONEY' => 'MONEY'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payment_method' => 'string',
            'blocked' => 'boolean',

        ];
    }
}
