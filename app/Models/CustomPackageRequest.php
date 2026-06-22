<?php

namespace App\Models;

use App\Enum\CustomPackageRequestStatus;
use Database\Factories\CustomPackageRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPackageRequest extends Model
{
    /** @use HasFactory<CustomPackageRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'destination',
        'travel_type',
        'travelers',
        'travel_date',
        'budget',
        'accommodation',
        'duration',
        'notes',
        'status',
        'locale',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'travelers' => 'integer',
            'travel_date' => 'date',
            'status' => CustomPackageRequestStatus::class,
        ];
    }
}
