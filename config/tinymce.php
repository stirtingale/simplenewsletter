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
        'wordcount',
        'paste'
    ],
    'toolbar' => 'undo redo | formatselect | bold italic | h1 h2 h3 | alignleft aligncenter alignright | bullist numlist | code',
    'formats' => [
        'bold' => ['inline' => 'strong'],
        'italic' => ['inline' => 'em']
    ],
    'valid_elements' => '+*[*]',
    'forced_root_block' => 'p',
    'height' => 400,
    'paste_enable_default_filters' => false,
    'paste_remove_styles_if_webkit' => false,
    'paste_data_images' => false,
    // 'paste_preprocess' => function(plugin, args) {
    //     args.content = args.content.replace(/<b>/g, '<strong>').replace(/<\/b>/g, '</strong>');
    // },
    'content_style' => 'body { font-family: Arial,sans-serif; font-size: 14px; line-height: 1.6; }',
    'convert_fonts_to_spans' => true,
    'extended_valid_elements' => 'strong[style],em[style],span[style]',
    'schema' => 'html5',
    'paste_webkit_styles' => "color font-size"
];
