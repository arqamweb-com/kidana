<?php

namespace App\Models;

use App\Enum\ApiPaymentMode;
use Database\Factories\ApiClientFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * A B2B partner that consumes the Partner API. Unrelated to {@see Partner}, which only holds
 * the partner logos shown on the website.
 */
class ApiClient extends Model implements AuthenticatableContract
{
    use Authenticatable;

    use HasApiTokens {
        tokenCan as tokenHasAbility;
    }

    /** @use HasFactory<ApiClientFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_email',
        'is_active',
        'markup_percent',
        'payment_mode',
        'allowed_abilities',
        'webhook_url',
        'webhook_secret',
        'ip_allowlist',
    ];

    protected $hidden = [
        'webhook_secret',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'markup_percent' => 'decimal:2',
            'payment_mode' => ApiPaymentMode::class,
            'allowed_abilities' => 'array',
            'webhook_secret' => 'encrypted',
            'ip_allowlist' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ApiClient $client): void {
            $client->webhook_secret ??= Str::random(40);
        });
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function requestLogs(): HasMany
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    /**
     * A token ability only counts while the client is still allowed to use it, so removing an
     * ability from the client takes effect on tokens that were issued before the change.
     */
    public function tokenCan(string $ability): bool
    {
        return in_array($ability, $this->allowed_abilities ?? [], true)
            && $this->tokenHasAbility($ability);
    }

    public function allowsIp(?string $ip): bool
    {
        $allowlist = array_values(array_filter($this->ip_allowlist ?? []));

        if ($allowlist === []) {
            return true;
        }

        return $ip !== null && IpUtils::checkIp($ip, $allowlist);
    }

    /**
     * The price this client sees for a Kidana base price.
     */
    public function priceFor(int|float|string $basePrice): int
    {
        return (int) round((float) $basePrice * (1 + (float) $this->markup_percent / 100));
    }

    /**
     * The Kidana base price that corresponds to a price quoted to this client.
     */
    public function basePriceFor(int|float|string $clientPrice): float
    {
        return (float) $clientPrice / (1 + (float) $this->markup_percent / 100);
    }
}
