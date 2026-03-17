<?php

namespace App\Filament\Resources;

use App\Enums\QualityGrade;
use App\Filament\Resources\ProductionRecordResource\Pages;
use App\Models\HenBatch;
use App\Models\ProductionRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductionRecordResource extends Resource
{
    protected static ?string $model = ProductionRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 20;

    public static function getNavigationLabel(): string
    {
        return __('Production');
    }

    public static function getModelLabel(): string
    {
        return __('Production Record');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Production Records');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Farm');
    }

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
                Forms\Components\Section::make('Production Entry')
                    ->schema([
                        Forms\Components\Select::make('hen_batch_id')
                            ->label('Hen Batch')
                            ->options(HenBatch::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('qty_morning')
                            ->label('Morning Qty')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('qty_afternoon')
                            ->label('Afternoon Qty')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('broken')
                            ->label('Broken')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\TextInput::make('dirty')
                            ->label('Dirty')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\Select::make('quality_grade')
                            ->label('Quality Grade')
                            ->options(QualityGrade::options()),
                    ])->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('henBatch.name')
                    ->label('Batch')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_morning')
                    ->label('Morning')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_afternoon')
                    ->label('Afternoon')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty_total')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('broken')
                    ->label('Broken')
                    ->numeric()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('dirty')
                    ->label('Dirty')
                    ->numeric()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('net_production')
                    ->label('Net')
                    ->numeric()
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('quality_grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn (?QualityGrade $state): string => $state?->color() ?? 'gray')
                    ->formatStateUsing(fn (?QualityGrade $state): string => $state?->label() ?? '-')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hen_batch_id')
                    ->label('Batch')
                    ->options(HenBatch::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('quality_grade')
                    ->label('Grade')
                    ->options(QualityGrade::options()),
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
            'index' => Pages\ListProductionRecords::route('/'),
            'create' => Pages\CreateProductionRecord::route('/create'),
            'edit' => Pages\EditProductionRecord::route('/{record}/edit'),
        ];
    }
}
