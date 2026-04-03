<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\ServiceArea;
use App\Models\State;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Find Cameroon
        $country = Country::where('country_name', 'Cameroon')
            ->orWhere('country_name', 'CAMEROON')
            ->first();

        if (!$country) {
            $this->command->error('Country "Cameroon" not found. Please ensure countries are seeded.');
            return;
        }

        $countryId = $country->id;

        // 2. Define Hierarchy
        $data = [
            'South West' => [
                'Limbe' => ['Limbe'],
                'Buea' => ['Buea'],
                'Mutengene' => ['Mutengene'],
                'Tiko' => ['Tiko'],
                'Idenau' => ['Idenau'],
                'Kumba' => ['Kumba'],
            ],
            'Littoral' => [
                'Douala' => ['Douala'],
            ],
            'Centre' => [
                'Yaoundé' => ['Yaoundé'],
            ],
            'East' => [
                'Bertoua' => ['Bertoua'],
            ],
            'Far North' => [
                'Maroua' => ['Maroua'],
            ],
        ];

        foreach ($data as $stateName => $cities) {
            // Create/Find State
            $state = State::firstOrCreate(
                ['state_name' => $stateName, 'country_id' => $countryId],
                ['status' => 1]
            );

            foreach ($cities as $cityName => $serviceAreas) {
                // Create/Find City
                $city = City::firstOrCreate(
                    ['city_name' => $cityName, 'state_id' => $state->id],
                    [
                        'country_id' => $countryId,
                        'status' => 1,
                        'latitude' => 0, // Will be updated by Osm API if needed
                        'longitude' => 0,
                    ]
                );

                foreach ($serviceAreas as $areaLocation) {
                    // Create/Find Service Area
                    ServiceArea::firstOrCreate(
                        ['location' => $areaLocation, 'city_id' => $city->id],
                        [
                            'latitude' => 0,
                            'longitude' => 0,
                        ]
                    );
                }
            }
        }

        $this->command->info('Location hierarchy for 10 service areas seeded successfully.');
    }
}
