<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
    
class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;
   protected $fillable = [
        'member_id',
        'date',
        'check_in',
        'check_out',
    ];
 
    protected $casts = [
        'date'      => 'date',
        'check_in'  => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];
 
    /* ══════════════════════════ العلاقات ══════════════════════════ */
 
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
 
    /* ══════════════════════════ Accessors ══════════════════════════ */
 
    /**
     * هل العضو لسه "جوه" (حضر ولسه ما انصرفش).
     */
    protected function isCheckedIn(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->check_in && ! $this->check_out,
        );
    }
 
    /**
     * مدة الزيارة (بالدقائق) لو انصرف، وإلا null.
     */
    protected function durationMinutes(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->check_in || ! $this->check_out) {
                    return null;
                }
 
                return $this->check_in->diffInMinutes($this->check_out);
            },
        );
    }
 
    /**
     * مدة الزيارة بصيغة "س د" جاهزة للعرض.
     */
    protected function durationLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $minutes = $this->duration_minutes;
 
                if ($minutes === null) {
                    return null;
                }
 
                $hours = intdiv($minutes, 60);
                $mins  = $minutes % 60;
 
                if ($hours > 0) {
                    return "{$hours} س {$mins} د";
                }
 
                return "{$mins} د";
            },
        );
    }
 
    /* ══════════════════════════ Scopes ══════════════════════════ */
 
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }
 
    public function scopeStillIn($query)
    {
        return $query->whereNull('check_out');
    }
}
