<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'public.support_email', 'value' => '', 'value_type' => 'string', 'is_public' => true],
            ['key' => 'public.support_whatsapp', 'value' => '', 'value_type' => 'string', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::query()->firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
