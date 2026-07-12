<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\Gender;
class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;
    protected $fillable = [
    'full_name',
    'phone',
    'gender',
    'birth_date',
    'address',
    'photo',
    'status',
    'notes',
];
// cast
protected $casts = [
    'birth_date' => 'date',
    'status' => 'boolean',
    'gender' => Gender::class,
];
public function subscriptions()
{
    return $this->hasMany(Subscription::class);
}

public function attendances()
{
    return $this->hasMany(Attendance::class);
}
protected function photoUrl(): Attribute
{
    return Attribute::make(
        get: fn () => $this->photo
            ? asset('storage/'.$this->photo)
            : asset('assets/avatars/face-1.jpg'),
    );
}
// get age from birth date

protected function age(): Attribute
{
    return Attribute::make(
        get: fn () => $this->birth_date?->age,
    );
}
}
