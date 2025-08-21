<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';          // <-- singular table
    protected $primaryKey = 'userid';   // <-- PK column
    public $timestamps = false;

    protected $fillable = ['name','email','password','phone','address','role'];
    protected $hidden = ['password','remember_token'];

    // If your PK is int auto-increment, this stays true (default)
    public $incrementing = true;
    protected $keyType = 'int';

    public function farmer()   { return $this->hasOne(Farmer::class, 'userid', 'userid'); }
    public function customer() { return $this->hasOne(Customer::class, 'userid', 'userid'); }
}
