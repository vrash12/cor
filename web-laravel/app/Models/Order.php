<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = '`order`'; // order can be a keyword; backticks are safe
    protected $primaryKey = 'orderid';
    public $timestamps = false;
    protected $fillable = ['customerid','orderdate','status','totalamount'];

    public function customer(){ return $this->belongsTo(Customer::class, 'customerid', 'customerid'); }
    public function items()   { return $this->hasMany(OrderItem::class, 'orderid', 'orderid'); }
}
