<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $countries = [
            'Bangladesh',
            'India',
            'Pakistan',
            'Nepal',
            'Sri Lanka',
            'United States',
            'United Kingdom',
            'Canada',
            'Australia',
            'Germany',
            'France',
            'Italy',
            'Spain',
            'Brazil',
            'Japan',
            'China'
        ];

        for ($i = 1; $i <= 100; $i++) {
            $country = $countries[array_rand($countries)];

            // Set realistic Bangladeshi values if selected
            if ($country === 'Bangladesh') {
                $name = $faker->firstName . ' ' . $faker->lastName;
                $company = 'BD ' . $faker->company;
                $city = $faker->randomElement(['Dhaka', 'Chittagong', 'Sylhet', 'Khulna']);
                $area = $faker->randomElement(['Gulshan', 'Dhanmondi', 'Uttara', 'Mirpur']);
                $postCode = $faker->randomElement(['1207', '1212', '1230', '1000']);
            } else {
                $name = $faker->name;
                $company = $faker->company;
                $city = $faker->city;
                $area = $faker->streetName;
                $postCode = $faker->postcode;
            }

            // Random date within last 10 days
            $randomDaysAgo = rand(0, 9); // 0 = today, 9 = 9 days ago
            $createdAt = Carbon::now()->subDays($randomDaysAgo)->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

            DB::table('contacts')->insert([
                'source' => $faker->randomElement(['Web Form', 'Referral', 'Manual Entry', 'Advertisement']),
                'name' => $name,
                'company_name' => $company,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'area' => $area,
                'post_code' => $postCode,
                'city' => $city,
                'country' => $country,
                'note' => $faker->sentence,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
