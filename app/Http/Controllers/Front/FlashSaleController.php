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

        $flashCategories = \App\Models\Category::where('status', 1)->get();
        $selectedCategory = $request->get('category');
        $sort = $request->get('sort');
        $selectedDate = $request->get('date', \Carbon\Carbon::today()->format('Y-m-d'));

        $flashProducts = collect();
        if ($selectedSlot) {
            $query = FlashSaleProduct::with('product')
                                ->where('time_slot_id', $selectedSlot->id)
                                ->where('status', 1)
                                ->whereDate('flash_date', '=', $selectedDate);

            if ($selectedCategory) {
                $query->whereHas('product', function($q) use ($selectedCategory) {
                    $q->where('category_id', $selectedCategory);
                });
            }

            // Apply Sorting
            if ($sort == 'price_asc') {
                $query->orderBy('flash_price', 'asc');
            } elseif ($sort == 'price_desc') {
                $query->orderBy('flash_price', 'desc');
            }

            $flashProducts = $query->get();

            // Handle sorting that depends on the related product model
            if ($sort == 'newest') {
                $flashProducts = $flashProducts->sortByDesc(function ($fp) {
                    return $fp->product->created_at ?? now();
                });
            } elseif ($sort == 'rating') {
                // Assuming product has a method or attribute for average rating
                $flashProducts = $flashProducts->sortByDesc(function ($fp) {
                    return $fp->product->ratings()->avg('rating') ?? 0;
                });
            } else {
                // Default: Popularity
                if (!$sort || $sort == 'popularity') {
                    $flashProducts = $flashProducts->sortByDesc(function ($fp) {
                        return $fp->product->views ?? 0;
                    });
                }
            }
        }

        $latest_products = \App\Models\Product::with('user')->whereStatus(1)->whereLatest(1)
            ->whereHas('user', function ($q) {
                $q->where('is_vendor', 2);
            })
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->get()
            ->chunk(4);

        return view('frontend.flash-sales', compact('timeSlots', 'selectedSlot', 'flashProducts', 'latest_products', 'flashCategories', 'sort', 'selectedCategory', 'selectedDate'));
    }
}
