<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $gym_name;
    public string $owner_name;
    public string $phone;
    public string $email;
    public string $address;
    public ?string $logo;
    public string $timezone;
    public string $facebook_url;
    public string $instagram_url;
    public string $whatsapp_number;
    public static function group(): string
    {
        return 'general';
    }
}