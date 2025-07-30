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