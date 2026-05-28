<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSaleTimeSlot;
use App\Models\FlashSaleProduct;

class FlashSaleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get all active time slots ordered by start_time
        $timeSlots = FlashSaleTimeSlot::where('status', 1)
                        ->orderBy('start_time', 'asc')
                        ->get();

        // 2. Identify the currently active time slot based on the current time
        $currentTime = now()->format('H:i:s');
        $activeSlot = $timeSlots->filter(function($slot) use ($currentTime) {
            return $currentTime >= $slot->start_time && $currentTime <= $slot->end_time;
        })->first();

        // If no slot is currently active, maybe the first one or next upcoming one is active?
        if (!$activeSlot && $timeSlots->count() > 0) {
            // Find next upcoming
            $activeSlot = $timeSlots->filter(function($slot) use ($currentTime) {
                return $slot->start_time > $currentTime;
            })->first();

            // If still null, just pick the first one
            if (!$activeSlot) {
                $activeSlot = $timeSlots->first();
            }
        }

        // The user can click a different time slot, so check for a requested slot_id
        $selectedSlotId = $request->get('slot', $activeSlot ? $activeSlot->id : null);
        $selectedSlot = $timeSlots->where('id', $selectedSlotId)->first();

        $flashProducts = collect();
        if ($selectedSlot) {
            $flashProducts = FlashSaleProduct::with('product')
                                ->where('time_slot_id', $selectedSlot->id)
                                ->where('status', 1)
                                ->where(function($query) use ($selectedSlot) {
                                    $query->whereRaw("TIMESTAMP(flash_date, ?) <= ? AND TIMESTAMP(flash_date, ?) > ?", [$selectedSlot->start_time, now(), $selectedSlot->start_time, now()->subHours(24)])
                                          ->orWhere(function($q) use ($selectedSlot) {
                                              $q->whereDate('flash_date', \Carbon\Carbon::today())
                                                ->whereRaw("TIMESTAMP(flash_date, ?) > ?", [$selectedSlot->start_time, now()]);
                                          });
                                })
                                ->get();
        }

        return view('frontend.flash-sales', compact('timeSlots', 'selectedSlot', 'flashProducts'));
    }
}
