<?php

namespace App\Helpers;

/**
 * Vite Helper - Generate asset tags for Vite-compiled assets
 */

if (!function_exists('vite_asset_tags')) {
    /**
     * Generate Vite asset tags by reading from manifest.json
     */
    function vite_asset_tags($entry = 'resources/css/app.css'): string
    {
        $manifestPath = FCPATH . 'build/.vite/manifest.json';
        
        if (!file_exists($manifestPath)) {
            // Development mode fallback
            return '<link rel="stylesheet" href="' . base_url('build/assets/app.css') . '">';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        if (!isset($manifest[$entry])) {
            return '';
        }

        $assetEntry = $manifest[$entry];
        $html = '';

        // Include CSS file
        if (isset($assetEntry['file'])) {
            $html .= '<link rel="stylesheet" href="' . base_url('build/' . $assetEntry['file']) . '">';
        }

        // Include any CSS imports
        if (isset($assetEntry['css'])) {
            foreach ($assetEntry['css'] as $css) {
                $html .= '<link rel="stylesheet" href="' . base_url('build/' . $css) . '">';
            }
        }

        // Include JS file
        if (isset($assetEntry['file']) && strpos($assetEntry['file'], '.js') !== false) {
            $html .= '<script type="module" src="' . base_url('build/' . $assetEntry['file']) . '"></script>';
        }

        return $html;
    }
}
