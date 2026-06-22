<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CrashCart extends Model {
    protected $fillable = ['name', 'location', 'status', 'contents', 'last_checked', 'checked_by'];
    protected $casts = ['last_checked' => 'datetime'];
}
