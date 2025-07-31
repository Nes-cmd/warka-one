<?php

if (!function_exists('csp_nonce')) {
    /**
     * Get the current CSP nonce for inline scripts and styles
     */
    function csp_nonce(): string
    {
        return request()->attributes->get('csp_nonce', '');
    }
}

if (!function_exists('csp_script')) {
    /**
     * Generate a script tag with CSP nonce
     */
    function csp_script(string $content): string
    {
        $nonce = csp_nonce();
        return "<script nonce=\"{$nonce}\">{$content}</script>";
    }
}

if (!function_exists('csp_style')) {
    /**
     * Generate a style tag with CSP nonce
     */
    function csp_style(string $content): string
    {
        $nonce = csp_nonce();
        return "<style nonce=\"{$nonce}\">{$content}</style>";
    }
}

if (!function_exists('sri_script')) {
    /**
     * Generate a script tag with SRI integrity and crossorigin
     */
    function sri_script(string $src, string $integrity, array $attributes = []): string
    {
        $attrs = [];
        $attrs[] = "src=\"{$src}\"";
        $attrs[] = "integrity=\"{$integrity}\"";
        $attrs[] = "crossorigin=\"anonymous\"";
        
        foreach ($attributes as $key => $value) {
            if (is_numeric($key)) {
                $attrs[] = $value; // Boolean attributes like 'defer'
            } else {
                $attrs[] = "{$key}=\"{$value}\"";
            }
        }
        
        return "<script " . implode(' ', $attrs) . "></script>";
    }
}

if (!function_exists('sri_link')) {
    /**
     * Generate a link tag with SRI integrity and crossorigin for stylesheets
     */
    function sri_link(string $href, string $integrity, array $attributes = []): string
    {
        $attrs = [];
        $attrs[] = "rel=\"stylesheet\"";
        $attrs[] = "href=\"{$href}\"";
        $attrs[] = "integrity=\"{$integrity}\"";
        $attrs[] = "crossorigin=\"anonymous\"";
        
        foreach ($attributes as $key => $value) {
            if (is_numeric($key)) {
                $attrs[] = $value;
            } else {
                $attrs[] = "{$key}=\"{$value}\"";
            }
        }
        
        return "<link " . implode(' ', $attrs) . ">";
    }
} 