<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'reviewid';
    public $timestamps = false;
    protected $fillable = ['customerid','productid','rating','comment','reviewdate'];
}
