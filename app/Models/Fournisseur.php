<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $dateFormat = 'Y-d-m H:i:s';
    use HasFactory;
}
