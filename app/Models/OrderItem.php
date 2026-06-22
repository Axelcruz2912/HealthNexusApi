<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {
    protected $fillable = ['purchase_order_id', 'medication_id', 'quantity', 'unit_price', 'subtotal'];
    public function order() { return $this->belongsTo(PurchaseOrder::class); }
    public function medication() { return $this->belongsTo(Medication::class); }
}
