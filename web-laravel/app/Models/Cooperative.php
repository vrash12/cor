<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    protected $table = 'cooperative';
    protected $primaryKey = 'cooperativeid';
    public $timestamps = false;
    protected $fillable = ['name','address','contactinfo','accreditationstatus','dateestablished'];
}
