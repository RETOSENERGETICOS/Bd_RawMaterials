<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'has_validation' => 'boolean',
        'dispatchable' => 'boolean'
    ];

    public function family(): BelongsTo {
        return $this->belongsTo(Family::class);
    }

    public function countrysu(): BelongsTo {
        return $this->belongsTo(Countrysu::class);
    }

    public function countryor(): BelongsTo {
        return $this->belongsTo(Countrysu::class);
    }

    public function hazard(): BelongsTo {
        return $this->belongsTo(Hazard::class);
    }

    public function king(): BelongsTo {
        return $this->belongsTo(King::class);
    }

    public function files(): MorphMany {
        return $this->morphMany(File::class, 'fileable');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany {
        return $this->hasMany(Log::class);
    }
}
