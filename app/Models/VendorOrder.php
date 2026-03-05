<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorOrder extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'order_id', 'order_number', 'user_id', 'qty', 'price', 'status', 'delivery_fee'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Order')->withDefault();
    }
}
