<?php

return [
    "entities" => [
        "redirect" => [
            "form" => \App\Cms\Redirects\Form::class,
            // "validator" => \App\Sharp\SpaceshipSharpValidator::class,
            "list" => \App\Cms\Redirects\Dashboard::class,
        ]
    ],
    "menu" => [
        [
            "type" => "page",
            "label" => "Redirects",
            "icon" => "fa-dashboard",
            "entity" => "redirect"
        ],
    ],
    "custom_url_segment" => "admin",
    "name" => "Redirector",
    "display_sharp_version_in_title" => false,
];