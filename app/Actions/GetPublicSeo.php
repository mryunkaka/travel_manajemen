<?php

namespace App\Actions;

class GetPublicSeo
{
    /**
     * @param  array<string, mixed>  $parameters
     * @param  array{
     *     company_name?: string,
     *     company_email?: ?string,
     *     company_phone?: ?string,
     *     company_address?: ?string,
     *     social_urls?: array<string, ?string>
     * }|null  $site
     * @return array{
     *     title: string,
     *     description: string,
     *     canonical: string,
     *     image: ?string,
     *     type: string,
     *     robots: string,
     *     site_name: string,
     *     structured_data: array<string, mixed>
     * }
     */
    public function handle(
        string $title,
        string $description,
        string $routeName,
        array $parameters = [],
        ?string $image = null,
        ?array $site = null,
    ): array {
        $base = rtrim(config('app.url'), '/');
        $canonical = $base.route($routeName, $parameters, false);
        $siteName = $site['company_name'] ?? config('app.name', 'Laravel');
        $imageUrl = $image ?: config('seo.default_image_url');
        $organizationId = $base.'/#organization';
        $websiteId = $base.'/#website';
        $address = filled($site['company_address'] ?? null)
            ? ['@type' => 'PostalAddress', 'streetAddress' => $site['company_address']]
            : null;
        $organization = array_filter([
            '@type' => 'TravelAgency',
            '@id' => $organizationId,
            'name' => $siteName,
            'url' => $base,
            'logo' => config('seo.default_image_url'),
            'email' => $site['company_email'] ?? null,
            'telephone' => $site['company_phone'] ?? null,
            'address' => $address,
            'sameAs' => array_values(array_filter($site['social_urls'] ?? [])),
        ], fn (mixed $value): bool => $value !== null && $value !== []);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'image' => $imageUrl,
            'type' => 'website',
            'robots' => config('seo.indexable') ? 'index, follow' : 'noindex, nofollow',
            'site_name' => $siteName,
            'structured_data' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    $organization,
                    [
                        '@type' => 'WebSite',
                        '@id' => $websiteId,
                        'url' => $base,
                        'name' => $siteName,
                        'inLanguage' => 'id-ID',
                        'publisher' => ['@id' => $organizationId],
                    ],
                    array_filter([
                        '@type' => 'WebPage',
                        '@id' => $canonical.'#webpage',
                        'url' => $canonical,
                        'name' => $title,
                        'description' => $description,
                        'inLanguage' => 'id-ID',
                        'isPartOf' => ['@id' => $websiteId],
                        'about' => ['@id' => $organizationId],
                        'primaryImageOfPage' => $imageUrl ? ['@type' => 'ImageObject', 'url' => $imageUrl] : null,
                    ], fn (mixed $value): bool => $value !== null),
                ],
            ],
        ];
    }
}
