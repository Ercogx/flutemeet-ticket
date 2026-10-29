<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Inerba\DbConfig\AbstractPageSettings;

class GeneralSettings extends AbstractPageSettings
{
    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    protected static ?string $title = 'General';

    // protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-wrench-screwdriver'; // Uncomment if you want to set a custom navigation icon

    // protected ?string $subheading = ''; // Uncomment if you want to set a custom subheading

    // protected static ?string $slug = 'general-settings'; // Uncomment if you want to set a custom slug

    protected function settingName(): string
    {
        return 'general';
    }

    /**
     * Provide default values.
     *
     * @return array<string, mixed>
     */
    public function getDefaultData(): array
    {
        return [
            'l_event_attendee_subject' => '',
            'l_event_attendee_email' => '',
            'l_event_payer_subject' => '',
            'l_event_payer_email' => '',
            'l_event_waitlist_subject' => '',
            'l_event_whitelist_email' => '',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendee Email')
                    ->components([
                        TextInput::make('l_event_attendee_subject')
                            ->label('Subject'),
                        RichEditor::make('l_event_attendee_email')
                            ->label('Email Body')
                            ->helperText('Available placeholders: {name}, {event}, {ticket_type}, {event_datetime}'),
                    ]),

                Section::make('Payer Email')
                    ->components([
                        TextInput::make('l_event_payer_subject')
                            ->label('Subject'),
                        RichEditor::make('l_event_payer_email')
                            ->label('Email Body')
                            ->helperText('Available placeholders: {name}, {event}, {total}, {numberf_of_tickets}'),
                    ]),

                Section::make('Waitlist Email')
                    ->components([
                        TextInput::make('l_event_waitlist_subject')
                            ->label('Subject'),
                        RichEditor::make('l_event_whitelist_email')
                            ->label('Email Body')
                            ->helperText('Available placeholders: {url}, {event}, {event_datetime}'),
                    ]),
            ])
            ->statePath('data');
    }
}
