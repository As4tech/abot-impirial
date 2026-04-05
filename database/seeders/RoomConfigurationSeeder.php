<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure room types exist
        $executiveSuite = RoomType::firstOrCreate(
            ['name' => 'Executive Suite'],
            ['description' => 'Premium room with full amenities', 'base_price' => 200, 'capacity' => 2, 'is_active' => true]
        );

        $standardRoom = RoomType::firstOrCreate(
            ['name' => 'Standard Room'],
            ['description' => 'Standard room with basic amenities', 'base_price' => 100, 'capacity' => 2, 'is_active' => true]
        );

        // Room configurations based on user's requirements
        $rooms = [
            // Executive Suites - Long Stay 200, Short Stay 150
            [
                'room_number' => '1',
                'room_type_id' => $executiveSuite->id,
                'long_price' => 200.00,
                'short_price' => 150.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Queen',
            ],
            // Executive Suite - Long Stay 250, Short Stay 150 (with fridge)
            [
                'room_number' => '2',
                'room_type_id' => $executiveSuite->id,
                'long_price' => 250.00,
                'short_price' => 150.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => true,
                'bed_type' => 'Queen',
            ],
            // Executive Suites - Long Stay 200, Short Stay 150
            [
                'room_number' => '3',
                'room_type_id' => $executiveSuite->id,
                'long_price' => 200.00,
                'short_price' => 150.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Queen',
            ],
            // Standard Room - Long Stay 150, Short Stay 100 (AC)
            [
                'room_number' => '4',
                'room_type_id' => $standardRoom->id,
                'long_price' => 150.00,
                'short_price' => 100.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
            // Executive Suite - Long Stay 200, Short Stay 150
            [
                'room_number' => '5',
                'room_type_id' => $executiveSuite->id,
                'long_price' => 200.00,
                'short_price' => 150.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Queen',
            ],
            // Standard Room - Long Stay 150, Short Stay 100 (AC)
            [
                'room_number' => '6',
                'room_type_id' => $standardRoom->id,
                'long_price' => 150.00,
                'short_price' => 100.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
            // Standard Room - Long Stay 100, Short Stay 70 (Fan)
            [
                'room_number' => '7',
                'room_type_id' => $standardRoom->id,
                'long_price' => 100.00,
                'short_price' => 70.00,
                'has_ac' => false,
                'has_fan' => true,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
            // Standard Room - Long Stay 150, Short Stay 100 (AC)
            [
                'room_number' => '8',
                'room_type_id' => $standardRoom->id,
                'long_price' => 150.00,
                'short_price' => 100.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
            // Standard Room - Long Stay 150, Short Stay 100 (AC)
            [
                'room_number' => '9',
                'room_type_id' => $standardRoom->id,
                'long_price' => 150.00,
                'short_price' => 100.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
            // Standard Room - Long Stay 80, Short Stay 70 (Fan - budget)
            [
                'room_number' => '10',
                'room_type_id' => $standardRoom->id,
                'long_price' => 80.00,
                'short_price' => 70.00,
                'has_ac' => false,
                'has_fan' => true,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Single',
            ],
            // Standard Room - Long Stay 80, Short Stay 70 (Fan - budget)
            [
                'room_number' => '11',
                'room_type_id' => $standardRoom->id,
                'long_price' => 80.00,
                'short_price' => 70.00,
                'has_ac' => false,
                'has_fan' => true,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Single',
            ],
            // Standard Room - Long Stay 150, Short Stay 100 (AC)
            [
                'room_number' => '12',
                'room_type_id' => $standardRoom->id,
                'long_price' => 150.00,
                'short_price' => 100.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
            // Standard Room - Long Stay 150, Short Stay 100 (AC)
            [
                'room_number' => '13',
                'room_type_id' => $standardRoom->id,
                'long_price' => 150.00,
                'short_price' => 100.00,
                'has_ac' => true,
                'has_fan' => false,
                'has_tv' => true,
                'has_fridge' => false,
                'bed_type' => 'Double',
            ],
        ];

        foreach ($rooms as $roomData) {
            $roomData['stay_type'] = 'long';
            $roomData['price'] = $roomData['long_price'];
            $roomData['type'] = $roomData['room_type_id'] === $executiveSuite->id ? 'Executive Suite' : 'Standard Room';
            $roomData['status'] = 'Available';

            Room::updateOrCreate(
                ['room_number' => $roomData['room_number']],
                $roomData
            );
        }

        $this->command->info('13 rooms configured successfully!');
        $this->command->info('Room summary:');
        $this->command->info('- Executive Suites: Rooms 1, 2, 3, 5');
        $this->command->info('- Standard Rooms AC: Rooms 4, 6, 8, 9, 12, 13');
        $this->command->info('- Standard Rooms Fan: Rooms 7, 10, 11');
    }
}
