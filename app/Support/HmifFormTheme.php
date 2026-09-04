<?php

namespace App\Support;

final class HmifFormTheme
{
    public const FONTS = ['Inter', 'Roboto', 'Arial', 'Georgia', 'Times New Roman', 'Verdana'];
    public const HEADER_SIZES = [20, 22, 24, 26, 28, 30, 32];
    public const QUESTION_SIZES = [12, 13, 14, 15, 16, 18];
    public const TEXT_SIZES = [11, 12, 13, 14, 15, 16];

    public static function defaults(): array
    {
        return [
            'header_font' => 'Inter', 'header_size' => 28,
            'question_font' => 'Inter', 'question_size' => 14,
            'text_font' => 'Inter', 'text_size' => 13,
            'primary_color' => '#f59e0b', 'background_color' => '#09090b',
            'surface_color' => '#18181b', 'header_image' => null,
        ];
    }

    public static function normalize(?array $theme): array
    {
        $theme = array_merge(self::defaults(), $theme ?? []);
        $theme['surface_color'] = self::surfaceFor($theme['background_color']);

        return $theme;
    }

    public static function surfaceFor(string $background): string
    {
        return self::isDark($background) ? '#18181b' : '#ffffff';
    }

    public static function isDark(string $hex): bool
    {
        $hex = ltrim($hex, '#');
        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) return true;
        [$r, $g, $b] = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];

        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 150;
    }

    public static function fontFamily(string $font): string
    {
        return match ($font) {
            'Georgia' => 'Georgia, serif',
            'Times New Roman' => '"Times New Roman", serif',
            'Arial' => 'Arial, sans-serif',
            'Verdana' => 'Verdana, sans-serif',
            'Roboto' => 'Roboto, Arial, sans-serif',
            default => 'Inter, system-ui, sans-serif',
        };
    }
}
