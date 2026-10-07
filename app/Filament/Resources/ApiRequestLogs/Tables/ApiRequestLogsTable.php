<?php

namespace App\Filament\Resources\ApiRequestLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApiRequestLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('apiClient:id,name'))
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('apiClient.name')
                    ->label('Partner'),
                TextColumn::make('method')
                    ->badge(),
                TextColumn::make('path')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 500 => 'danger',
                        $state >= 400 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
                TextColumn::make('duration_ms')
                    ->label('Duration')
                    ->suffix(' ms')
                    ->sortable(),
                TextColumn::make('ip')
                    ->label('IP')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('api_client_id')
                    ->label('Partner')
                    ->relationship('apiClient', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('failed')
                    ->label('Result')
                    ->placeholder('All requests')
                    ->trueLabel('Failed (4xx/5xx)')
                    ->falseLabel('Successful')
                    ->queries(
                        true: fn (Builder $query) => $query->where('status', '>=', 400),
                        false: fn (Builder $query) => $query->where('status', '<', 400),
                    ),
            ]);
    }
}
