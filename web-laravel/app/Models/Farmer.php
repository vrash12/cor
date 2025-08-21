<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $table = 'farmer';

    protected $fillable = [
        'userid', 
        'cooperativeid', 
        'farmname', 
        'pickup_address',          // New field
        'business_name',           // New field
        'registered_address',      // New field
        'taxpayer_id',             // New field
        'business_registration_certificate',  // New field (File path)
        'proof_of_identity',       // New field (File path)
        'seller_type',             // New field
        'certification',
        'description',
        'status',
        'businesspermit',
        'tin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperativeid');
    }
}
