<?php

namespace App\Services;

class DeliveryRouteService
{
    /**
     * Sort stops by distance to buyer DESC (farthest first).
     *
     * @param  array  $stops Array of stop data (must include lat/lng if available)
     * @return array Sorted stops
     */
    public function optimizePickupSequence(array $stops, ?float $buyerLat, ?float $buyerLng): array
    {
        if (is_null($buyerLat) || is_null($buyerLng)) {
            // No coordinates, return as-is or sort by ID/Sequence
            return $stops;
        }

        foreach ($stops as &$stop) {
            if (! empty($stop['lat']) && ! empty($stop['lng'])) {
                $stop['distance_to_buyer'] = $this->haversine($buyerLat, $buyerLng, (float) $stop['lat'], (float) $stop['lng']);
            } else {
                $stop['distance_to_buyer'] = 0; // Or some default
            }
        }

        // Sort farthest to buyer FIRST
        usort($stops, function ($a, $b) {
            return $b['distance_to_buyer'] <=> $a['distance_to_buyer'];
        });

        // Assign sequences after sorting
        foreach ($stops as $index => &$stop) {
            $stop['sequence'] = $index + 1;
        }

        return $stops;
    }

    /**
     * Haversine formula to calculate distance between two points in km.
     */
    public function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
