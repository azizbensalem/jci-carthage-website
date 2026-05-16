<?php

namespace App\Support;

use Illuminate\Support\Str;

class Schema
{
    /**
     * Return the base site URL.
     */
    public static function siteUrl(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    /**
     * Resolve a relative path or URL into an absolute URL.
     */
    public static function absoluteUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return url($path);
    }

    /**
     * Return the schema.org organization id.
     */
    public static function organizationId(): string
    {
        return self::siteUrl() . '#organization';
    }

    /**
     * Return the schema.org website id.
     */
    public static function websiteId(): string
    {
        return self::siteUrl() . '#website';
    }

    /**
     * Return a reference to the organization schema.
     */
    public static function organizationReference(): array
    {
        return ['@id' => self::organizationId()];
    }

    /**
     * Return a reference to the website schema.
     */
    public static function websiteReference(): array
    {
        return ['@id' => self::websiteId()];
    }

    /**
     * Build the main organization schema.
     */
    public static function organization(): array
    {
        return self::clean([
            '@context' => 'https://schema.org',
            '@type' => config('seo.organization.type', 'Organization'),
            '@id' => self::organizationId(),
            'name' => config('seo.organization.name'),
            'legalName' => config('seo.organization.legal_name'),
            'url' => self::siteUrl(),
            'description' => config('seo.default_description'),
            'email' => config('seo.organization.email'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => self::absoluteUrl(config('seo.organization.logo')),
            ],
            'image' => self::absoluteUrl(config('seo.organization.logo')),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => config('seo.organization.city'),
                'addressRegion' => config('seo.organization.region'),
                'addressCountry' => config('seo.organization.country'),
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => config('seo.organization.email'),
                'availableLanguage' => ['fr', 'en'],
            ],
            'sameAs' => config('seo.organization.same_as', []),
        ]);
    }

    /**
     * Build the main website schema.
     */
    public static function website(): array
    {
        return self::clean([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => self::websiteId(),
            'url' => self::siteUrl(),
            'name' => config('seo.organization.name'),
            'description' => config('seo.default_description'),
            'publisher' => self::organizationReference(),
            'inLanguage' => app()->getLocale(),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('blog.index') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ]);
    }

    /**
     * Build a generic web page schema.
     */
    public static function page(string $type, string $name, ?string $description = null, ?string $url = null, array $extra = []): array
    {
        return self::clean(array_merge([
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => $name,
            'description' => $description,
            'url' => $url ?: request()->fullUrl(),
            'inLanguage' => app()->getLocale(),
            'isPartOf' => self::websiteReference(),
            'publisher' => self::organizationReference(),
        ], $extra));
    }

    /**
     * Build a breadcrumb schema.
     */
    public static function breadcrumb(array $items): array
    {
        $elements = [];

        foreach (array_values($items) as $index => $item) {
            $elements[] = self::clean([
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'] ?? null,
                'item' => $item['url'] ?? null,
            ]);
        }

        return self::clean([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ]);
    }

    /**
     * Remove null and empty values recursively.
     */
    private static function clean($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        $cleaned = [];

        foreach ($value as $key => $item) {
            $item = self::clean($item);

            if ($item === null || $item === '' || $item === []) {
                continue;
            }

            $cleaned[$key] = $item;
        }

        return $cleaned;
    }
}
