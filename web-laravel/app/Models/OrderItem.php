<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'orderitem';
    protected $primaryKey = 'orderitemid';
    public $timestamps = false;
    protected $fillable = ['orderid','productid','quantity','price'];

    public function product(){ return $this->belongsTo(Product::class, 'productid', 'productid'); }
}
