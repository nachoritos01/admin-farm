<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.activity-log';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public function getTitle(): string
    {
        return __('Activity Log');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->where('tenant_id', session('tenant_id'))
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label(__('User'))
                    ->default(__('System')),
                Tables\Columns\TextColumn::make('event')
                    ->label(__('Event'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __(ucfirst($state))),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label(__('Model'))
                    ->formatStateUsing(fn (string $state): string => __(class_basename($state))),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->formatStateUsing(fn (string $state): string => __(ucfirst($state)))
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label(__('Event'))
                    ->options([
                        'created' => __('Created'),
                        'updated' => __('Updated'),
                        'deleted' => __('Deleted'),
                    ]),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label(__('Model'))
                    ->options(
                        fn () => Activity::query()
                        ->where('tenant_id', session('tenant_id'))
                        ->distinct()
                        ->pluck('subject_type')
                        ->filter()
                        ->mapWithKeys(fn (string $type) => [$type => __(class_basename($type))])
                        ->toArray()
                    ),
            ]);
    }

    public static function getNavigationLabel(): string
    {
        return __('Activity');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }
}
