<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Corrects country_code (ISO 3166-1 alpha-2) and dial_code (E.164) for the existing
     * `countries` rows, keyed by id — the values previously stored were shuffled/misaligned
     * (e.g. Argentina had country_code "AW" and dial_code "AO"). phone_length is the typical
     * national significant number length, used only for client-side input validation.
     */
    public function run(): void
    {
        $corrections = [
            1  => ['country_code' => 'ET', 'dial_code' => '+251', 'phone_length' => 9],
            2  => ['country_code' => 'AW', 'dial_code' => '+297', 'phone_length' => 7],
            3  => ['country_code' => 'GB', 'dial_code' => '+44', 'phone_length' => 10],
            4  => ['country_code' => 'ES', 'dial_code' => '+34', 'phone_length' => 9],
            5  => ['country_code' => 'SH', 'dial_code' => '+290', 'phone_length' => 4],
            6  => ['country_code' => 'BO', 'dial_code' => '+591', 'phone_length' => 8],
            7  => ['country_code' => 'FM', 'dial_code' => '+691', 'phone_length' => 7],
            8  => ['country_code' => 'VG', 'dial_code' => '+1284', 'phone_length' => 7],
            9  => ['country_code' => 'AL', 'dial_code' => '+355', 'phone_length' => 9],
            10 => ['country_code' => 'AR', 'dial_code' => '+54', 'phone_length' => 10],
            11 => ['country_code' => 'HK', 'dial_code' => '+852', 'phone_length' => 8],
            12 => ['country_code' => 'JP', 'dial_code' => '+81', 'phone_length' => 10],
            13 => ['country_code' => 'VG', 'dial_code' => '+1284', 'phone_length' => 7],
            14 => ['country_code' => 'MO', 'dial_code' => '+853', 'phone_length' => 8],
            15 => ['country_code' => 'TM', 'dial_code' => '+993', 'phone_length' => 8],
            16 => ['country_code' => 'FI', 'dial_code' => '+358', 'phone_length' => 9],
            17 => ['country_code' => 'IE', 'dial_code' => '+353', 'phone_length' => 9],
            18 => ['country_code' => 'CK', 'dial_code' => '+682', 'phone_length' => 5],
            19 => ['country_code' => 'JP', 'dial_code' => '+81', 'phone_length' => 10],
            20 => ['country_code' => 'NF', 'dial_code' => '+672', 'phone_length' => 6],
            21 => ['country_code' => 'RU', 'dial_code' => '+7', 'phone_length' => 10],
            22 => ['country_code' => 'SG', 'dial_code' => '+65', 'phone_length' => 8],
            23 => ['country_code' => 'TH', 'dial_code' => '+66', 'phone_length' => 9],
            24 => ['country_code' => 'KY', 'dial_code' => '+1345', 'phone_length' => 7],
            25 => ['country_code' => 'SI', 'dial_code' => '+386', 'phone_length' => 8],
            26 => ['country_code' => 'GH', 'dial_code' => '+233', 'phone_length' => 9],
            27 => ['country_code' => 'GW', 'dial_code' => '+245', 'phone_length' => 7],
            28 => ['country_code' => 'JO', 'dial_code' => '+962', 'phone_length' => 9],
        ];

        foreach ($corrections as $id => $data) {
            Country::whereKey($id)->update($data);
        }
    }
}
