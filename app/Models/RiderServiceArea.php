<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class RiderServiceArea extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['rider_id', 'service_area_id'];
    public function serviceArea()
    {
        return $this->belongsTo(ServiceArea::class, 'service_area_id');
    }
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
