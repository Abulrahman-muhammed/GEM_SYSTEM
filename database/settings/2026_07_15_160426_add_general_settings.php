<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
    $this->migrator->add('general.gym_name', 'Gym Management');
    $this->migrator->add('general.owner_name', '');
    $this->migrator->add('general.phone', '');
    $this->migrator->add('general.email', '');
    $this->migrator->add('general.address', '');
    $this->migrator->add('general.logo', null);
    $this->migrator->add('general.timezone', 'Africa/Cairo');
    $this->migrator->add('general.facebook_url','');
    $this->migrator->add('general.instagram_url', '');
    $this->migrator->add('general.whatsapp_number', '');
    }
};
