<?php

namespace App\Filament\Resources\EventWhitelists;

use App\Filament\Resources\EventWhitelists\Pages\ManageEventWhitelists;
use App\Models\EventWhitelist;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventWhitelistResource extends Resource
{
    protected static ?string $model = EventWhitelist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckBadge;

    protected static ?string $recordTitleAttribute = 'email';

    protected static ?string $modelLabel = 'Event Waitlist';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('number_adult_ticket')
                    ->required()
                    ->numeric(),
                TextInput::make('number_child_ticket')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('email')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('event.name'),
                TextColumn::make('number_adult_ticket')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('number_child_ticket')
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
                SelectFilter::make('event_id')
                    ->relationship('event', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEventWhitelists::route('/'),
        ];
    }
}
