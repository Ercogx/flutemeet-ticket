<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\ManageEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Toggle::make('is_active')
                    ->inline(false),
                DateTimePicker::make('start_date')
                    ->native(false)
                    ->seconds(false),
                DateTimePicker::make('end_date')
                    ->native(false)
                    ->seconds(false)
                    ->after('start_date'),
                TextInput::make('number_adult_ticket')
                    ->required()
                    ->numeric(),
                TextInput::make('number_child_ticket')
                    ->required()
                    ->numeric(),
                TextInput::make('price_adult_ticket')
                    ->prefix('€')
                    ->required()
                    ->numeric(),
                TextInput::make('price_child_ticket')
                    ->prefix('€')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount(['paidChildOrderTickets', 'paidAdultOrderTickets']))
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('number_adult_ticket')
                    ->label("Adult Ticket's Amount")
                    ->numeric()
                    ->state(fn (Event $record) => sprintf('%s (sold: %s)', $record->number_adult_ticket, $record->paid_adult_order_tickets_count))
                    ->sortable(),
                TextColumn::make('number_child_ticket')
                    ->label("Child Ticket's Amount")
                    ->state(fn (Event $record) => sprintf('%s (sold: %s)', $record->number_child_ticket, $record->paid_child_order_tickets_count))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_adult_ticket')
                    ->label("Adult Ticket's Price")
                    ->prefix('€')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_child_ticket')
                    ->label("Child Ticket's Price")
                    ->prefix('€')
                    ->numeric()
                    ->sortable(),
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
                TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                Action::make('waitlist')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('success')
                    ->url(fn (Event $record) => route('whitelist.show', $record), true),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEvents::route('/'),
        ];
    }
}
