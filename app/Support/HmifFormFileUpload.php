<?php

namespace App\Support;

class HmifFormFileUpload
{
    public const TYPE_MIMES = [
        'pdf' => ['application/pdf'],
        'document' => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'spreadsheet' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        'presentation' => ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'],
        'image' => ['image/jpeg', 'image/png', 'image/webp'],
        'video' => ['video/mp4', 'video/webm'],
        'audio' => ['audio/mpeg', 'audio/wav', 'audio/ogg'],
        'archive' => ['application/zip', 'application/x-7z-compressed', 'application/x-rar-compressed'],
    ];

    public static function defaults(): array
    {
        return ['max_files' => 1, 'max_file_size_mb' => min(10, max(self::sizeOptions())), 'allowed_types_enabled' => false, 'allowed_types' => []];
    }

    public static function normalize(?array $settings): array
    {
        $settings = array_replace(self::defaults(), $settings ?? []);
        $settings['max_files'] = in_array((int) $settings['max_files'], [1, 2, 3, 5, 10], true) ? (int) $settings['max_files'] : 1;
        $settings['max_file_size_mb'] = in_array((int) $settings['max_file_size_mb'], self::sizeOptions(), true) ? (int) $settings['max_file_size_mb'] : max(self::sizeOptions());
        $settings['allowed_types_enabled'] = (bool) $settings['allowed_types_enabled'];
        $settings['allowed_types'] = array_values(array_intersect((array) $settings['allowed_types'], array_keys(self::TYPE_MIMES)));
        return $settings;
    }

    public static function sizeOptions(): array
    {
        $limit = self::serverLimitMb();
        $options = array_values(array_filter([1, 5, 10, 25, 50], fn (int $size) => $size <= $limit));
        return $options ?: [1];
    }

    public static function serverLimitMb(): int
    {
        return max(1, min(self::iniMb(ini_get('upload_max_filesize')), self::iniMb(ini_get('post_max_size'))));
    }

    public static function allowedMimes(array $settings): array
    {
        if (! $settings['allowed_types_enabled']) return array_values(array_unique(array_merge(...array_values(self::TYPE_MIMES))));
        if ($settings['allowed_types'] === []) return ['application/x-hmif-no-file-type-selected'];
        return array_values(array_unique(array_merge(...array_map(fn ($type) => self::TYPE_MIMES[$type], $settings['allowed_types']))));
    }

    private static function iniMb(string|false $value): int
    {
        if (! $value) return PHP_INT_MAX;
        $number = (float) $value;
        return match (strtolower(substr(trim($value), -1))) {'g' => (int) ($number * 1024), 'k' => max(1, (int) ($number / 1024)), default => (int) $number};
    }
}
