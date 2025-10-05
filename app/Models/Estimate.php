<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Estimate extends Model
{
use HasFactory;


protected $fillable = [
'kloc','mode','salary','drivers','eaf','pm','tdev','p','monthly_cost','total_cost'
];


protected $casts = [
'drivers' => AsArrayObject::class,
'kloc' => 'decimal:3',
'salary' => 'decimal:2',
'eaf' => 'decimal:6',
'pm' => 'decimal:6',
'tdev' => 'decimal:6',
'p' => 'decimal:6',
'monthly_cost' => 'decimal:2',
'total_cost' => 'decimal:2',
];
}