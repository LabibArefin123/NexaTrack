<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $fakerBd = Faker::create('en_US');     // Bengali locale for Bangladeshi format
        $fakerEn = Faker::create('en_US');     // English for other countries

        $countries = ['Bangladesh', 'India', 'USA', 'UK', 'Germany', 'Japan', 'Canada', 'Australia'];
        $softwares = ['NexaTrack', 'VisiQ', 'BidTrack', 'TimeTracks', 'SalesPulse'];
        $sources = ['Website', 'Phone Call', 'Referral', 'Email Campaign', 'Walk-in'];

        foreach (range(1, 100) as $i) {
            $isBangladeshi = $i % 2 === 0;

            $faker = $isBangladeshi ? $fakerBd : $fakerEn;

            $createdAt = Carbon::now()->subDays(rand(0, 10))->setTime(rand(8, 20), rand(0, 59));

            Customer::create([
                'software'      => $faker->randomElement($softwares),
                'name'          => $faker->name,
                'email'         => $faker->unique()->safeEmail,
                'phone'         => $faker->phoneNumber,
                'company_name'  => $faker->company,
                'address'       => $faker->address,
                'area'          => $faker->citySuffix,
                'city'          => $faker->city,
                'country'       => $isBangladeshi ? 'Bangladesh' : $faker->randomElement($countries),
                'post_code'     => $faker->postcode,
                'note'          => $faker->sentence,
                'source'        => $faker->randomElement($sources),
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);
        }
    }
}
