<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MembershipPlan;
use App\Models\NewsletterList;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $roleNames = ['super_admin', 'admin', 'editor', 'moderator', 'journalist', 'author', 'subscriber'];
        foreach ($roleNames as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Permissions
        $permissions = [
            'access-admin', 'manage-posts', 'publish-posts', 'manage-categories',
            'manage-users', 'manage-roles', 'manage-ads', 'manage-settings',
            'manage-themes', 'manage-media', 'manage-memberships', 'view-analytics',
            'manage-rss', 'manage-newsletter', 'moderate-comments',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        Role::findByName('super_admin')->givePermissionTo(Permission::all());
        Role::findByName('admin')->givePermissionTo(['access-admin', 'manage-posts', 'publish-posts', 'manage-categories', 'manage-ads', 'manage-media', 'view-analytics', 'moderate-comments', 'manage-newsletter', 'manage-rss']);
        Role::findByName('editor')->givePermissionTo(['access-admin', 'manage-posts', 'publish-posts', 'manage-categories', 'manage-media', 'moderate-comments']);
        Role::findByName('moderator')->givePermissionTo(['access-admin', 'moderate-comments']);
        Role::findByName('journalist')->givePermissionTo(['access-admin', 'manage-posts']);
        Role::findByName('author')->givePermissionTo(['access-admin', 'manage-posts']);

        // Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@laranews.test'],
            [
                'name'              => 'Super Admin',
                'username'          => 'admin',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'locale'            => 'dv',
                'is_active'         => true,
            ]
        );
        $adminUser->assignRole('super_admin');

        // Default Theme
        Theme::firstOrCreate(
            ['slug' => 'default-red'],
            [
                'name'        => 'Default Red',
                'slug'        => 'default-red',
                'description' => 'Default news theme with red primary color',
                'is_active'   => true,
                'color_palette' => ['primary' => '#DC2626', 'secondary' => '#1F2937', 'accent' => '#F59E0B', 'background' => '#F9FAFB'],
                'typography'    => ['heading_font' => 'Inter', 'body_font' => 'Inter'],
                'homepage_blocks' => [
                    'hero_slider' => true, 'featured_grid' => true, 'latest_news' => true,
                    'category_sections' => true, 'video_section' => true, 'trending_sidebar' => true,
                    'tags_cloud' => true, 'weather_widget' => false, 'prayer_times' => true,
                ],
            ]
        );

        // Default Settings
        $settingsData = [
            ['group' => 'general', 'key' => 'site_name',      'value' => config('app.name'), 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_tagline',   'value' => 'Maldivian News',   'type' => 'string'],
            ['group' => 'general', 'key' => 'posts_per_page', 'value' => '15',               'type' => 'integer'],
            ['group' => 'general', 'key' => 'logo_path',      'value' => '',                 'type' => 'string'],
            ['group' => 'seo',     'key' => 'google_analytics_id',     'value' => '', 'type' => 'string'],
            ['group' => 'seo',     'key' => 'google_search_console',   'value' => '', 'type' => 'string'],
            ['group' => 'widgets', 'key' => 'show_weather',            'value' => 'false', 'type' => 'boolean'],
            ['group' => 'widgets', 'key' => 'show_prayer_times',       'value' => 'true',  'type' => 'boolean'],
            ['group' => 'social',  'key' => 'facebook_url',            'value' => '', 'type' => 'string'],
            ['group' => 'social',  'key' => 'twitter_url',             'value' => '', 'type' => 'string'],
            ['group' => 'social',  'key' => 'instagram_url',           'value' => '', 'type' => 'string'],
            ['group' => 'social',  'key' => 'youtube_url',             'value' => '', 'type' => 'string'],
            ['group' => 'social',  'key' => 'telegram_url',            'value' => '', 'type' => 'string'],
        ];
        foreach ($settingsData as $s) {
            Setting::firstOrCreate(['group' => $s['group'], 'key' => $s['key']], $s);
        }

        // Sample Categories
        $categoriesData = [
            ['dv' => ['name' => 'ޤައުމީ', 'slug' => 'qawmi'], 'en' => ['name' => 'National', 'slug' => 'national'], 'icon' => '🇲🇻', 'color' => '#DC2626', 'featured' => true],
            ['dv' => ['name' => 'ބައިނަލްއަޤްވާމީ', 'slug' => 'bainalaqu'], 'en' => ['name' => 'International', 'slug' => 'international'], 'icon' => '🌍', 'color' => '#2563EB', 'featured' => true],
            ['dv' => ['name' => 'ސިޔާސަތު', 'slug' => 'siyaasath'], 'en' => ['name' => 'Politics', 'slug' => 'politics'], 'icon' => '🏛️', 'color' => '#7C3AED', 'featured' => true],
            ['dv' => ['name' => 'ވިޔަފާރި', 'slug' => 'viyafaari'], 'en' => ['name' => 'Business', 'slug' => 'business'], 'icon' => '💼', 'color' => '#059669', 'featured' => false],
            ['dv' => ['name' => 'ކުޅިވަރު', 'slug' => 'kulhivaru'], 'en' => ['name' => 'Sports', 'slug' => 'sports'], 'icon' => '⚽', 'color' => '#EA580C', 'featured' => false],
            ['dv' => ['name' => 'ތައުލީމް', 'slug' => 'thauleem'], 'en' => ['name' => 'Education', 'slug' => 'education'], 'icon' => '📚', 'color' => '#0891B2', 'featured' => false],
            ['dv' => ['name' => 'ސިއްހަތު', 'slug' => 'sihhath'], 'en' => ['name' => 'Health', 'slug' => 'health'], 'icon' => '🏥', 'color' => '#16A34A', 'featured' => false],
            ['dv' => ['name' => 'ޓެކްނޮލޮޖީ', 'slug' => 'technology'], 'en' => ['name' => 'Technology', 'slug' => 'technology'], 'icon' => '💻', 'color' => '#4F46E5', 'featured' => false],
        ];

        foreach ($categoriesData as $idx => $catData) {
            $category = Category::firstOrCreate(
                ['slug' => $catData['en']['slug']],
                [
                    'slug'        => $catData['en']['slug'],
                    'icon'        => $catData['icon'],
                    'color'       => $catData['color'],
                    'is_active'   => true,
                    'is_featured' => $catData['featured'],
                    'order'       => $idx + 1,
                ]
            );
            foreach (['dv', 'en'] as $locale) {
                $category->translations()->firstOrCreate(
                    ['locale' => $locale],
                    ['name' => $catData[$locale]['name'], 'slug' => $catData[$locale]['slug']]
                );
            }
        }

        // Membership Plans
        MembershipPlan::firstOrCreate(['slug' => 'monthly'], [
            'name'       => json_encode(['dv' => 'މަހަކަށް', 'en' => 'Monthly']),
            'slug'       => 'monthly',
            'price'      => 79.00,
            'currency'   => 'MVR',
            'interval'   => 'monthly',
            'is_active'  => true,
            'sort_order' => 1,
            'features'   => json_encode(['Unlimited premium articles', 'Ad-free reading', 'Newsletter access']),
        ]);

        MembershipPlan::firstOrCreate(['slug' => 'annual'], [
            'name'        => json_encode(['dv' => 'އަހަރަކަށް', 'en' => 'Annual']),
            'slug'        => 'annual',
            'price'       => 799.00,
            'currency'    => 'MVR',
            'interval'    => 'yearly',
            'is_active'   => true,
            'is_featured' => true,
            'sort_order'  => 2,
            'features'    => json_encode(['Everything in Monthly', 'Save 15%', 'Priority support', 'Exclusive reports']),
        ]);

        // Default Newsletter List
        NewsletterList::firstOrCreate(['slug' => 'general'], [
            'name'        => 'General',
            'slug'        => 'general',
            'description' => 'General newsletter for all subscribers',
            'is_active'   => true,
        ]);

        $this->command->info('✅ Database seeded!');
        $this->command->info('📧 admin@laranews.test / password');
    }
}
