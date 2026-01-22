<?php

namespace App\Filament\Resources\Orders;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Filament\Resources\OrderTickets\OrderTicketResource;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ReceiptPercent;

    protected static ?string $recordTitleAttribute = 'pay_pal_id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_whitelist_id')
                    ->relationship('eventWhitelist', 'id')
                    ->searchable()
                    ->preload(),
                TextInput::make('session')
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('payer_name'),
                TextInput::make('payer_email')
                    ->email(),
                TextInput::make('pay_pal_id'),
                TextInput::make('pay_pal_capture'),
                Select::make('status')
                    ->options(OrderStatus::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pay_pal_id')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('eventWhitelist.id')
                    ->searchable(),
                TextColumn::make('event.name'),
                TextColumn::make('payer_name')
                    ->searchable(),
                TextColumn::make('payer_email')
                    ->searchable(),
                TextColumn::make('pay_pal_id')
                    ->searchable(),
                TextColumn::make('pay_pal_capture')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('order_tickets_count')
                    ->counts('orderTickets')
                    ->url(fn (Order $record) => OrderTicketResource::getUrl('index', ['filters[order_id][value]' => $record->id])),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(OrderStatus::class),
                SelectFilter::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ReplicateAction::make()
                    ->excludeAttributes(['order_tickets_count'])
                    ->modalWidth(Width::Small),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrders::route('/'),
        ];
    }
}
