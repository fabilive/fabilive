<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeliveryFee extends Model
{
    protected $table = 'delivery_fee';
    protected $fillable = ['weight','start_range','end_range','fee'];
}
