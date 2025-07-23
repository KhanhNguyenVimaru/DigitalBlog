<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Generate breadcrumb links from current URL segments.
     * @return array
     */
    public static function generateBreadcrumbLinks()
    {
        $segments = request()->segments();
        $links = [
            ['label' => 'Home', 'url' => url('/')],
        ];
        $url = '';
        foreach ($segments as $segment) {
            if (is_numeric($segment)) continue; // Bỏ qua segment là số
            $url .= '/' . $segment;
            $links[] = [
                'label' => ucfirst(str_replace(['-', '_'], ' ', $segment)),
                'url' => url($url)
            ];
        }
        return $links;
    }
}
