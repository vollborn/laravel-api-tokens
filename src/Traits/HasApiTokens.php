<?php

namespace Vollborn\LaravelApiTokens\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Vollborn\LaravelApiTokens\Models\ApiToken;

/**
 * include this trait in your user model
 */
trait HasApiTokens
{
    /**
     * @return HasMany
     */
    public function apiTokens(): HasMany
    {
        return $this->hasMany(ApiToken::class);
    }

    /**
     * @return ApiToken|null
     */
    public function generateApiToken(): ?ApiToken
    {
        return ApiToken::generate($this->id);
    }
}
