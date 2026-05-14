<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin  = 'super_admin';
    case Admin       = 'admin';
    case Editor      = 'editor';
    case Moderator   = 'moderator';
    case Journalist  = 'journalist';
    case Author      = 'author';
    case Subscriber  = 'subscriber';

    public function label(): string
    {
        return match($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin      => 'Admin',
            self::Editor     => 'Editor',
            self::Moderator  => 'Moderator',
            self::Journalist => 'Journalist',
            self::Author     => 'Author',
            self::Subscriber => 'Subscriber',
        };
    }

    public function canPublish(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Admin, self::Editor, self::Journalist]);
    }

    public function canWritePosts(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Admin, self::Editor, self::Journalist, self::Author]);
    }

    public function canAccessAdmin(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Admin, self::Editor, self::Moderator, self::Journalist]);
    }

    public function canManageUsers(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Admin]);
    }

    public function isStaff(): bool
    {
        return $this !== self::Subscriber;
    }
}
