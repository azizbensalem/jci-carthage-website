<?php

return [
    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'JCI Carthage est une organisation locale de jeunes leaders engagés qui developpe des projets, des initiatives et des actions a impact pour la communaute de Carthage.'
    ),

    'default_image' => env('SEO_DEFAULT_IMAGE', '/images/jci-carthage.png'),

    'organization' => [
        'type' => env('SEO_ORGANIZATION_TYPE', 'NGO'),
        'name' => env('SEO_ORGANIZATION_NAME', 'JCI Carthage'),
        'legal_name' => env('SEO_LEGAL_NAME', 'Junior Chamber International Carthage'),
        'email' => env('SEO_CONTACT_EMAIL', 'jcicarthage.olm@gmail.com'),
        'city' => env('SEO_CITY', 'Carthage'),
        'region' => env('SEO_REGION', 'Tunis'),
        'country' => env('SEO_COUNTRY', 'TN'),
        'logo' => env('SEO_LOGO_PATH', '/images/jci-carthage.png'),
        'same_as' => array_values(array_filter([
            env('SEO_FACEBOOK_URL'),
            env('SEO_INSTAGRAM_URL'),
            env('SEO_LINKEDIN_URL'),
            env('SEO_YOUTUBE_URL'),
        ])),
    ],
];
