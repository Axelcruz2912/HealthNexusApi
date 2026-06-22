<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bed extends Model {
    protected $fillable = ['floor', 'room_number', 'bed_number', 'status', 'type'];
}
