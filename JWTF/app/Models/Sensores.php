<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Sensores extends Model
{
    use HasFactory;
    protected $connection ='mongodb';
    protected $colecction = 'data_sensores';

    public $timestamps = false;
}
