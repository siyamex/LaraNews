<?php

namespace App\Enums;

enum PostStatus: string
{
    case Draft     = 'draft';
    case Review    = 'review';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived  = 'archived';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Draft',
            self::Review    => 'Under Review',
            self::Scheduled => 'Scheduled',
            self::Published => 'Published',
            self::Archived  => 'Archived',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft     => 'gray',
            self::Review    => 'amber',
            self::Scheduled => 'blue',
            self::Published => 'green',
            self::Archived  => 'red',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Draft     => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
            self::Review    => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
            self::Scheduled => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            self::Published => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            self::Archived  => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
        };
    }

    public function isPublic(): bool
    {
        return $this === self::Published;
    }

    public static function editableStatuses(): array
    {
        return [self::Draft, self::Review, self::Scheduled, self::Published];
    }
}
