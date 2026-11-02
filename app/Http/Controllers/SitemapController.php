<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $base = rtrim(config('app.url'), '/');
        $urls = collect(['home', 'packages.index', 'about', 'contact'])
            ->map(fn (string $name): array => [
                'loc' => $base.route($name, [], false),
                'lastmod' => null,
            ])
            ->concat(Package::query()
                ->where('is_active', true)
                ->orderBy('id')
                ->get(['slug', 'updated_at'])
                ->map(fn (Package $package): array => [
                    'loc' => $base.route('packages.show', ['package' => $package->slug], false),
                    'lastmod' => $package->updated_at?->toIso8601String(),
                ]));

        return response()->view('sitemap', ['urls' => $urls], 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
