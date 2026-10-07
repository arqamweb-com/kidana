<?php

namespace App\Filament\Resources\ApiClients\Schemas;

use App\Enum\ApiAbility;
use App\Enum\ApiPaymentMode;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ApiClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Partner')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('contact_email')
                                ->email()
                                ->maxLength(255),
                            Radio::make('payment_mode')
                                ->label('Who collects the payment?')
                                ->options(ApiPaymentMode::options())
                                ->default(ApiPaymentMode::PartnerCollect->value)
                                ->required()
                                ->columnSpanFull(),
                            TextInput::make('markup_percent')
                                ->label('Markup')
                                ->helperText('Added to every price the partner sees and books. Use a negative value for a discount.')
                                ->numeric()
                                ->suffix('%')
                                ->default(0)
                                ->minValue(-100)
                                ->maxValue(500)
                                ->required(),
                        ])
                        ->columns(2),

                    Section::make('Webhooks')
                        ->description('Kidana posts booking status changes here, signed with the secret (X-Kidana-Signature).')
                        ->schema([
                            TextInput::make('webhook_url')
                                ->label('Webhook URL')
                                ->url()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('webhook_secret')
                                ->password()
                                ->revealable()
                                ->default(fn (): string => Str::random(40))
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ]),
                ])
                    ->columnSpan(2),

                Group::make([
                    Section::make('Access')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                            CheckboxList::make('allowed_abilities')
                                ->label('Allowed abilities')
                                ->options(ApiAbility::options())
                                ->default(ApiAbility::values())
                                ->required(),
                            TagsInput::make('ip_allowlist')
                                ->label('IP allowlist')
                                ->placeholder('Add an IP or CIDR range')
                                ->helperText('Leave empty to allow any IP.'),
                        ]),
                ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
