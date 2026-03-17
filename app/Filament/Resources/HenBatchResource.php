<?php

namespace App\Filament\Resources;

use App\Enums\HenBatchStatus;
use App\Filament\Resources\HenBatchResource\Pages;
use App\Filament\Resources\HenBatchResource\RelationManagers;
use App\Models\HenBatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HenBatchResource extends Resource
{
    protected static ?string $model = HenBatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';

    protected static ?string $navigationLabel = 'Hen Batches';

    protected static ?string $modelLabel = 'Hen Batch';

    protected static ?string $pluralModelLabel = 'Hen Batches';

    protected static ?string $navigationGroup = 'Farm';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        return hasModule('production');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('production');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Batch Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Batch Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Batch A-2026'),
                        Forms\Components\TextInput::make('breed')
                            ->label('Breed')
                            ->maxLength(255)
                            ->placeholder('e.g., Hy-Line Brown'),
                        Forms\Components\TextInput::make('initial_count')
                            ->label('Initial Count')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\TextInput::make('current_count')
                            ->label('Current Count')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\TextInput::make('age_weeks')
                            ->label('Age (weeks)')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(HenBatchStatus::options())
                            ->default('active')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('breed')
                    ->label('Breed')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('current_count')
                    ->label('Hens')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age_weeks')
                    ->label('Age (wks)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (HenBatchStatus $state): string => $state->color())
                    ->formatStateUsing(fn (HenBatchStatus $state): string => $state->label()),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(HenBatchStatus::options()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\MovementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHenBatches::route('/'),
            'create' => Pages\CreateHenBatch::route('/create'),
            'edit' => Pages\EditHenBatch::route('/{record}/edit'),
        ];
    }
}
