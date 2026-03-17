<?php

namespace App\Filament\Resources;

use App\Enums\SupplierCategory;
use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Suppliers';

    protected static ?string $modelLabel = 'Supplier';

    protected static ?string $pluralModelLabel = 'Suppliers';

    protected static ?string $navigationGroup = 'Farm';

    protected static ?int $navigationSort = 40;

    public static function canAccess(): bool
    {
        return hasModule('suppliers');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('suppliers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Supplier Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Contact Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options(SupplierCategory::options())
                            ->default('other')
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
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Contact')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (SupplierCategory $state): string => $state->color())
                    ->formatStateUsing(fn (SupplierCategory $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expenses_count')
                    ->label('Expenses')
                    ->counts('expenses')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options(SupplierCategory::options()),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
