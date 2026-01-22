<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum OrderStatus: string implements HasColor, HasLabel
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case REJECTED = 'rejected';
    case REFUNDED = 'refunded';

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::PAID => 'success',
            self::REJECTED => 'danger',
            self::REFUNDED => 'warning',
        };
    }

    public function getLabel(): string
    {
        return Str::headline($this->value);
    }
}
