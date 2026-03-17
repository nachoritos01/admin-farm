<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoyaltyRewardResource\Pages;
use App\Models\LoyaltyReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LoyaltyRewardResource extends Resource
{
    protected static ?string $model = LoyaltyReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?int $navigationSort = 50;

    public static function getNavigationLabel(): string
    {
        return __('Loyalty Rewards');
    }

    public static function getModelLabel(): string
    {
        return __('Reward');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Loyalty Rewards');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public static function canAccess(): bool
    {
        return hasModule('loyalty');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('loyalty');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Reward Details'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(2)
                            ->maxLength(500),
                        Forms\Components\Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'discount_percent' => __('Discount (%)'),
                                'free_month' => __('Free Month'),
                                'free_months' => __('Free Months'),
                                'storage_upgrade' => __('Storage Upgrade'),
                                'feature_unlock' => __('Feature Unlock'),
                                'plan_upgrade' => __('Plan Upgrade'),
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('points_cost')
                            ->label(__('Points Cost'))
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('value')
                            ->label(__('Value'))
                            ->numeric()
                            ->helperText(__('E.g. 5.00 for 5% discount, 10.00 for 10 GB storage')),
                        Forms\Components\TextInput::make('icon')
                            ->label(__('Icon'))
                            ->maxLength(50)
                            ->default('gift')
                            ->helperText(__('Heroicon name without prefix (e.g. tag, fire, sparkles)')),
                    ])->columns(2),

                Forms\Components\Section::make(__('Availability'))
                    ->schema([
                        Forms\Components\Select::make('min_tier')
                            ->label(__('Minimum Tier'))
                            ->options([
                                0 => __('Bronze (All)'),
                                1 => __('Silver+'),
                                2 => __('Gold+'),
                                3 => __('VIP only'),
                            ])
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                Tables\Columns\TextColumn::make('points_cost')
                    ->label(__('Points'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('Value'))
                    ->numeric(2)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('min_tier')
                    ->label(__('Min Tier'))
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => __('All'),
                        1 => __('Silver+'),
                        2 => __('Gold+'),
                        3 => __('VIP'),
                        default => '-',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoyaltyRewards::route('/'),
            'create' => Pages\CreateLoyaltyReward::route('/create'),
            'edit' => Pages\EditLoyaltyReward::route('/{record}/edit'),
        ];
    }
}
