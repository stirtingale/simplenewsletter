<?php

return [
    'selector' => '#editor',
    'plugins' => [
        'advlist',
        'autolink',
        'lists',
        'link',
        'image',
        'charmap',
        'preview',
        'anchor',
        'searchreplace',
        'visualblocks',
        'code',
        'fullscreen',
        'insertdatetime',
        'media',
        'table',
        'help',
        'wordcount'
    ],
    'toolbar' => 'undo redo | formatselect | bold italic | h1 h2 h3 | alignleft aligncenter alignright | bullist numlist | code',
    'formats' => [
        'bold' => ['inline' => 'strong'],
        'italic' => ['inline' => 'em']
    ],
    // 'valid_elements' => '+*[*]',
    'valid_elements' => 'p,h1,h2,h3,h4,h5,h6,ul,ol,li,strong,em,a[href|target|title],br',
    'forced_root_block' => 'p',
    'height' => 400,
    'paste_enable_default_filters' => false,
    'paste_remove_styles_if_webkit' => false,
    'paste_data_images' => false,
    'content_style' => 'body { font-family: Arial,sans-serif; font-size: 14px; line-height: 1.6; }',
    'convert_fonts_to_spans' => false,
    'extended_valid_elements' => 'strong[style],em[style]',
    'schema' => 'html5',
    'paste_webkit_styles' => "color font-size"
];
