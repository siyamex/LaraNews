<?php

namespace App\Enums;

enum PaywallType: string
{
    case None = 'none';
    case Fade = 'fade';
    case Hard = 'hard';

    public function label(): string
    {
        return match($this) {
            self::None => 'No Paywall (Free)',
            self::Fade => 'Fade Paywall (partial preview)',
            self::Hard => 'Hard Paywall (no preview)',
        };
    }

    public function requiresMembership(): bool
    {
        return $this !== self::None;
    }
}
