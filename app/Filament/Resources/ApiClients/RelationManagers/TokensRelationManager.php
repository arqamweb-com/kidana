<?php

namespace App\Filament\Resources\ApiClients\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Attributes\On;

class TokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    protected static ?string $title = 'API tokens';

    #[On('refreshTokens')]
    public function refreshTokens(): void
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('abilities')
                    ->badge(),
                TextColumn::make('last_used_at')
                    ->dateTime()
                    ->placeholder('Never'),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->placeholder('Never'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Revoke')
                    ->modalHeading('Revoke token')
                    ->modalDescription('Requests made with this token will be rejected immediately.'),
            ]);
    }
}
