<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum TicketType: string implements HasColor, HasLabel
{
    case ADULT = 'Adult';
    case CHILD = 'Child';

    public function getColor(): string
    {
        return match ($this) {
            self::ADULT => 'success',
            self::CHILD => 'warning',
        };
    }

    public function getLabel(): string
    {
        return Str::headline($this->value);
    }
}
