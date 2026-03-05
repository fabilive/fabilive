<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DistanceFee extends Model
{
    protected $fillable = ['distance_start_range','distance_end_range','fee'];
}
