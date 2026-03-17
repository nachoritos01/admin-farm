<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Enums\PaymentMethod;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Payments');
    }

    public static function getModelLabel(): string
    {
        return __('Payment');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->step(0.01)
                    ->prefix('$')
                    ->required()
                    ->minValue(0.01)
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('fillBalance')
                            ->label(__('Pay balance'))
                            ->icon('heroicon-o-banknotes')
                            ->action(function (Forms\Set $set) {
                                /** @var Order $order */
                                $order = $this->getOwnerRecord();
                                $set('amount', number_format($order->balance, 2, '.', ''));
                            }),
                    )
                    ->rules([
                        fn (): \Closure => function (string $attribute, $value, \Closure $fail) {
                            /** @var Order $order */
                            $order = $this->getOwnerRecord();
                            $balance = $order->balance;
                            if ((float) $value > $balance) {
                                $fail(__('Amount ($:amount) cannot exceed balance ($:balance).', ['amount' => $value, 'balance' => $balance]));
                            }
                        },
                    ]),

                Forms\Components\Select::make('method')
                    ->label(__('Payment Method'))
                    ->options(PaymentMethod::options())
                    ->required()
                    ->default(PaymentMethod::Cash->value),

                Forms\Components\TextInput::make('reference')
                    ->label(__('Reference'))
                    ->maxLength(100)
                    ->placeholder(__('Transfer number, voucher, etc.')),

                Forms\Components\Textarea::make('notes')
                    ->label(__('Notes'))
                    ->rows(2),

                Forms\Components\DateTimePicker::make('received_at')
                    ->label(__('Payment Date'))
                    ->required()
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money()
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->label(__('Method'))
                    ->badge()
                    ->color(fn (PaymentMethod $state): string => $state->color())
                    ->formatStateUsing(fn (PaymentMethod $state): string => $state->label()),

                Tables\Columns\TextColumn::make('reference')
                    ->label(__('Reference'))
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('received_at')
                    ->label(__('Payment Date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Recorded'))
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('received_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Record Payment'))
                    ->visible(function (): bool {
                        /** @var Order $order */
                        $order = $this->getOwnerRecord();

                        return $order->balance > 0;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['received_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
