<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'staff_profile_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'guest_name',
        'guest_email',
        'guest_phone',
        'token',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}