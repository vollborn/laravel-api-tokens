<?php

namespace Vollborn\LaravelApiTokens\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use function now;

class ApiToken extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'token',
        'created_at',
        'expires_at'
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param int|null $userId
     * @return static|null
     */
    public static function generate(?int $userId = null): ?static
    {
        $id = $userId ?? Auth::id();
        if (!$id) {
            return null;
        }

        $token = Str::random(128);
        $exists = static::query()->where('token', $token)->exists();

        if ($exists) {
            return static::generate($id);
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return static::query()->create([
            'user_id'    => $id,
            'token'      => $token,
            'created_at' => now(),
            'expires_at' => now()->addDays(3)
        ]);
    }
}
