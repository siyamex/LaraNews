<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePwaIcons extends Command
{
    protected $signature = 'pwa:icons';
    protected $description = 'Generate placeholder PWA icons';

    public function handle(): void
    {
        $dir = public_path('images/pwa');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $sizes = [
            'icon-192'  => 192,
            'icon-512'  => 512,
            'badge-72'  => 72,
        ];

        foreach ($sizes as $name => $size) {
            $path = "{$dir}/{$name}.png";
            if (file_exists($path)) {
                $this->line("  Skipping {$name}.png (already exists)");
                continue;
            }

            $img   = imagecreatetruecolor($size, $size);
            $red   = imagecolorallocate($img, 220, 38, 38);
            $white = imagecolorallocate($img, 255, 255, 255);

            imagefilledrectangle($img, 0, 0, $size, $size, $red);

            if ($size >= 72) {
                $fontX = (int) ($size * 0.25);
                $fontY = (int) ($size * 0.55);
                imagestring($img, min(5, (int) ($size / 30)), $fontX, $fontY, 'N', $white);
            }

            imagepng($img, $path);
            imagedestroy($img);

            $this->info("  Created {$name}.png");
        }

        $this->info('PWA icons generated.');
    }
}
