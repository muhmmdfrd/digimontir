<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'admin_id',
        'technician_id',
        'customer_id',
        'status_id',
        'description_by_admin',
        'scheduled_date',
        'lat_check_in',
        'lng_check_in',
        'check_in_photo_path',
        'check_out_photo_path',
        'lat_check_out',
        'lng_check_out',
        'description_by_technician',
        'rating',
        'review_by_admin',
        'completed_at',
        'closed_at',
    ];

    /**
     * Get the casts array.
     * Laravel 11/12 convention is casts() method, but here we can define $casts property
     * or return it from casts method.
     * Let's use the property for simplicity.
     */
    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
