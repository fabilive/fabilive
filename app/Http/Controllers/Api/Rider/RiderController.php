<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Resources\ServiceAreaResource;
use App\Models\RiderServiceArea;
use Illuminate\Http\Request;

class RiderController
{
    public function storeServiceArea(Request $request)
    {
        $request->validate([
            'area' => 'required|exists:service_areas,id',
        ]);
        $rider = auth('rider-api')->user();
        $exists = RiderServiceArea::where('rider_id', $rider->id)
            ->where('service_area_id', $request->area)
            ->first();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Service area already added.',
            ], 409);
        }
        $riderServiceArea = RiderServiceArea::create([
            'rider_id' => $rider->id,
            'service_area_id' => $request->area,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Service area added successfully.',
            'data' => new ServiceAreaResource($riderServiceArea->serviceArea),
        ]);
    }

    public function deleteServiceArea($id)
    {
        try {
            // Get authenticated rider
            $rider = auth('rider-api')->user();
            // Find the service area record by rider_id + service_area_id
            $area = RiderServiceArea::where('rider_id', $rider->id)
                ->where('service_area_id', $id)  // 👈 match by service_area_id instead of id
                ->first();
            // If not found, return clear response
            if (! $area) {
                return response()->json([
                    'status' => false,
                    'message' => 'Service area not found for this rider.',
                ], 404);
            }
            // Delete the record
            $area->delete();

            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Service area deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateServiceArea(Request $request)
    {
        try {
            // ✅ Validate that both old and new service area IDs exist in the service_areas table
            $request->validate([
                'id' => 'required|exists:service_areas,id',   // old area id
                'area' => 'required|exists:service_areas,id',  // new area id
            ]);
            // ✅ Get authenticated rider
            $rider = auth('rider-api')->user();
            // ✅ Find the rider_service_area record based on rider_id and old service_area_id
            $riderServiceArea = RiderServiceArea::where('rider_id', $rider->id)
                ->where('service_area_id', $request->id)
                ->first();
            // ✅ If not found, return a clear message
            if (! $riderServiceArea) {
                return response()->json([
                    'status' => false,
                    'message' => 'Service area not found for this rider.',
                ], 404);
            }
            // ✅ Update the service_area_id with the new one
            $riderServiceArea->update([
                'service_area_id' => $request->area,
            ]);
            // ✅ Load the updated service area relationship
            $updatedServiceArea = new \App\Http\Resources\ServiceAreaResource($riderServiceArea->serviceArea);

            // ✅ Return success response
            return response()->json([
                'status' => true,
                'message' => 'Service area updated successfully.',
                'data' => [
                    'service_area' => $updatedServiceArea,
                ],
            ]);
        } catch (\Exception $e) {
            // ✅ Catch any unexpected errors
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
