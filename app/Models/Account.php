<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','address', 'checked', 'description', 'interest', 'date_of_birth', 'email', 'account'
    ];

    public $timestamps = false;

    public function creditcard(){
        return $this->hasMany(Creditcard::class);
    }

}
