<?php

namespace App\Filament\Resources\ApiClients\Pages;

use App\Enum\ApiAbility;
use App\Filament\Resources\ApiClients\ApiClientResource;
use App\Filament\Resources\ApiClients\Widgets\ApiClientStatsWidget;
use App\Models\ApiClient;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @property ApiClient $record
 */
class EditApiClient extends EditRecord
{
    protected static string $resource = ApiClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->generateTokenAction(),
            DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ApiClientStatsWidget::class,
        ];
    }

    protected function generateTokenAction(): Action
    {
        return Action::make('generateToken')
            ->label('Generate token')
            ->icon(Heroicon::OutlinedKey)
            ->modalDescription('The token is shown once, right after it is created. Send it to the partner over a secure channel.')
            ->schema([
                TextInput::make('name')
                    ->label('Token name')
                    ->placeholder('Production')
                    ->required()
                    ->maxLength(255),
                CheckboxList::make('abilities')
                    ->options(fn (): array => Arr::only(
                        ApiAbility::options(),
                        $this->record->allowed_abilities ?? [],
                    ))
                    ->default(fn (): array => $this->record->allowed_abilities ?? [])
                    ->required(),
                DatePicker::make('expires_at')
                    ->label('Expires on')
                    ->helperText('Leave empty for a token that never expires.')
                    ->minDate(now()->addDay()),
            ])
            ->action(function (array $data): void {
                $token = $this->record->createToken(
                    $data['name'],
                    $data['abilities'],
                    filled($data['expires_at'] ?? null) ? Carbon::parse($data['expires_at'])->endOfDay() : null,
                );

                Notification::make()
                    ->title('Token created. Copy it now, it will not be shown again.')
                    ->body($token->plainTextToken)
                    ->success()
                    ->persistent()
                    ->send();

                $this->dispatch('refreshTokens');
            });
    }
}
