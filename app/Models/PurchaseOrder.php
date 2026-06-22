<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PurchaseOrder extends Model {
    protected $fillable = ['po_number', 'provider_id', 'status', 'total_amount', 'expected_delivery', 'received_date', 'notes'];
    protected $casts = ['expected_delivery' => 'date', 'received_date' => 'date'];
    public function provider() { return $this->belongsTo(Provider::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
}
