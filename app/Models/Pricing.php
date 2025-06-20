<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricing extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'duration', 'price'];

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class, 'pricing_id');
    }

    public function isSubsribedByUser($userId): bool {
        return $this->transactions()
            ->where('user_id', $userId)
            ->where('is_paid', true)
            ->where('ended_at', '>=', now())
            ->exists();
    }
}
