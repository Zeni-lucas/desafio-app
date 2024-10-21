<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   use HasFactory;

   protected $fillable = [
      'name',
      'quantity',
      'valor'
   ];

   public function transactionProduct(){
      return $this->hasMany(transactionProduct::class);
   }
}
