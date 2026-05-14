<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstallerController extends Controller
{
    private string $lockFile;

    public function __construct()
    {
        $this->lockFile = storage_path('installed');
    }

    public function index()
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        return view('install.index', ['step' => 1]);
    }

    public function requirements()
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        $checks = $this->runRequirementsCheck();
        $allPassed = collect($checks)->every(fn($c) => $c['pass']);

        return view('install.requirements', compact('checks', 'allPassed'));
    }

    public function database()
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        return view('install.database');
    }

    public function testDatabase(Request $request)
    {
        $validated = $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|integer',
            'db_name'     => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            $pdo = new \PDO(
                "mysql:host={$validated['db_host']};port={$validated['db_port']};dbname={$validated['db_name']}",
                $validated['db_username'],
                $validated['db_password'] ?? '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            return response()->json(['success' => true, 'message' => 'Connection successful.']);
        } catch (\PDOException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function setupDatabase(Request $request)
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        $validated = $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|integer',
            'db_name'     => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        $this->writeEnvValue('DB_HOST', $validated['db_host']);
        $this->writeEnvValue('DB_PORT', $validated['db_port']);
        $this->writeEnvValue('DB_DATABASE', $validated['db_name']);
        $this->writeEnvValue('DB_USERNAME', $validated['db_username']);
        $this->writeEnvValue('DB_PASSWORD', $validated['db_password'] ?? '');

        return redirect()->route('install.site');
    }

    public function site()
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        return view('install.site');
    }

    public function setupSite(Request $request)
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        $validated = $request->validate([
            'app_name'    => 'required|string|max:100',
            'app_url'     => 'required|url',
            'app_locale'  => 'required|in:dv,en',
            'app_env'     => 'required|in:production,local',
        ]);

        $this->writeEnvValue('APP_NAME', '"' . $validated['app_name'] . '"');
        $this->writeEnvValue('APP_URL', $validated['app_url']);
        $this->writeEnvValue('APP_LOCALE', $validated['app_locale']);
        $this->writeEnvValue('APP_ENV', $validated['app_env']);
        $this->writeEnvValue('APP_DEBUG', $validated['app_env'] === 'production' ? 'false' : 'true');

        return redirect()->route('install.admin');
    }

    public function admin()
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        return view('install.admin');
    }

    public function runInstall(Request $request)
    {
        if (file_exists($this->lockFile)) {
            return redirect('/');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Generate app key if not set
            if (str_contains(env('APP_KEY', ''), 'base64:') === false) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Create admin user
            $user = \App\Models\User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('super_admin');

            // Seed roles/permissions if needed
            Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder', '--force' => true]);

            // Cache config
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('storage:link', ['--force' => true]);

            // Write lock file
            file_put_contents($this->lockFile, date('Y-m-d H:i:s') . "\nInstalled by: " . $validated['email']);

            return redirect()->route('install.complete');
        } catch (Exception $e) {
            return back()->withErrors(['install' => 'Installation failed: ' . $e->getMessage()]);
        }
    }

    public function complete()
    {
        return view('install.complete');
    }

    private function runRequirementsCheck(): array
    {
        return [
            ['label' => 'PHP >= 8.2',       'pass' => version_compare(PHP_VERSION, '8.2.0', '>='),   'current' => PHP_VERSION],
            ['label' => 'PDO MySQL',         'pass' => extension_loaded('pdo_mysql'),                  'current' => extension_loaded('pdo_mysql') ? 'Loaded' : 'Missing'],
            ['label' => 'BCMath',            'pass' => extension_loaded('bcmath'),                     'current' => extension_loaded('bcmath') ? 'Loaded' : 'Missing'],
            ['label' => 'GD',                'pass' => extension_loaded('gd'),                         'current' => extension_loaded('gd') ? 'Loaded' : 'Missing'],
            ['label' => 'Intl',              'pass' => extension_loaded('intl'),                       'current' => extension_loaded('intl') ? 'Loaded' : 'Missing'],
            ['label' => 'Mbstring',          'pass' => extension_loaded('mbstring'),                   'current' => extension_loaded('mbstring') ? 'Loaded' : 'Missing'],
            ['label' => 'OpenSSL',           'pass' => extension_loaded('openssl'),                    'current' => extension_loaded('openssl') ? 'Loaded' : 'Missing'],
            ['label' => 'Tokenizer',         'pass' => extension_loaded('tokenizer'),                  'current' => extension_loaded('tokenizer') ? 'Loaded' : 'Missing'],
            ['label' => 'XML',               'pass' => extension_loaded('xml'),                        'current' => extension_loaded('xml') ? 'Loaded' : 'Missing'],
            ['label' => 'storage/ writable', 'pass' => is_writable(storage_path()),                   'current' => is_writable(storage_path()) ? 'Writable' : 'Not writable'],
            ['label' => '.env exists',       'pass' => file_exists(base_path('.env')),                 'current' => file_exists(base_path('.env')) ? 'Found' : 'Missing'],
        ];
    }

    private function writeEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');

        if (! file_exists($path)) {
            copy(base_path('.env.example'), $path);
        }

        $env = file_get_contents($path);

        if (preg_match("/^{$key}=.*/m", $env)) {
            $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
        } else {
            $env .= "\n{$key}={$value}";
        }

        file_put_contents($path, $env);
    }
}
