<?php

namespace App\Filament\Resources;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentMethod;
use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Expenses';

    protected static ?string $modelLabel = 'Expense';

    protected static ?string $pluralModelLabel = 'Expenses';

    protected static ?string $navigationGroup = 'Farm';

    protected static ?int $navigationSort = 50;

    public static function canAccess(): bool
    {
        return hasModule('expenses');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('expenses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Expense Details')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->prefix('$')
                            ->minValue(0.01),
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options(ExpenseCategory::options())
                            ->default('other')
                            ->required(),
                        Forms\Components\Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::active()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(PaymentMethod::options())
                            ->default('cash')
                            ->required(),
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
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (ExpenseCategory $state): string => $state->color())
                    ->formatStateUsing(fn (ExpenseCategory $state): string => $state->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (PaymentMethod $state): string => $state->color())
                    ->formatStateUsing(fn (PaymentMethod $state): string => $state->label())
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options(ExpenseCategory::options()),
                Tables\Filters\SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->options(Supplier::pluck('name', 'id')),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
