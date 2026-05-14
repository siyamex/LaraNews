<?php

namespace App\Enums;

enum CommentStatus: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Spam     = 'spam';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Pending',
            self::Approved => 'Approved',
            self::Spam     => 'Spam',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Pending  => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
            self::Approved => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            self::Spam     => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
            self::Rejected => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
        };
    }

    public function isVisible(): bool
    {
        return $this === self::Approved;
    }
}
