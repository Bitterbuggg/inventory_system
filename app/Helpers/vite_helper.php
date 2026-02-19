<?php

if (! function_exists('vite_asset_tags')) {
    function vite_asset_tags(string $entry = 'resources/js/app.js'): string
    {
        $isDev = ENVIRONMENT !== 'production';

        if ($isDev) {
            return implode("\n", [
                '<script type="module" src="http://localhost:5173/@vite/client"></script>',
                '<script type="module" src="http://localhost:5173/' . esc($entry, 'attr') . '"></script>',
            ]);
        }

        $manifestPath = FCPATH . 'build/.vite/manifest.json';

        if (! is_file($manifestPath)) {
            return '';
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (! isset($manifest[$entry])) {
            return '';
        }

        $tags = [];

        if (! empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $cssFile) {
                $tags[] = '<link rel="stylesheet" href="/build/' . esc($cssFile, 'attr') . '">';
            }
        }

        $tags[] = '<script type="module" src="/build/' . esc($manifest[$entry]['file'], 'attr') . '"></script>';

        return implode("\n", $tags);
    }
}
