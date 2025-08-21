<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customerid';
    public $timestamps = false;
    protected $fillable = ['userid'];

    public function user(){ return $this->belongsTo(User::class, 'userid', 'userid'); }
}
