# Email Newsletter HTML Formatter

A PHP-based tool for cleaning and standardizing HTML content from Google Docs for use in email newsletters.

## Features

- Preserves semantic HTML structure while removing unnecessary styling
- Standardizes fonts, colors, and spacing
- Makes content email-client friendly
- WYSIWYG editor interface using TinyMCE
- Custom link styling
- List formatting
- Whitespace normalization

## Installation

1. Clone the repository
2. Install dependencies:
```bash
composer require tinymce/tinymce:^6.0 symfony/dom-crawler:^3.4 symfony/css-selector:^3.4
```

## Project Structure

```
newsletter/
├── composer.json
├── config/
│   └── tinymce.php       # TinyMCE editor configuration
├── src/
│   ├── EmailFormatter.php # Main formatting logic
│   └── HtmlCleaner.php   # HTML cleaning and standardization
├── templates/
│   ├── base.html.php     # Base template with TinyMCE integration
│   └── editor.html.php   # Editor interface template
└── public/
    └── index.php         # Application entry point
```

## How It Works

### 1. Content Input
- User pastes content into TinyMCE editor
- Editor preserves basic HTML structure while cleaning initial formatting

### 2. HTML Cleaning (HtmlCleaner.php)
- Removes Google Docs specific attributes
- Standardizes fonts to Helvetica/Arial family
- Normalizes line heights
- Standardizes font sizes to predefined scales
- Converts RGB colors to hex format
- Formats lists and paragraphs consistently
- Adds custom link styling (#ff5538)

### 3. Email Formatting (EmailFormatter.php)
- Wraps content in email-friendly template
- Adds responsive styling
- Ensures compatibility across email clients

## HTML Cleaning Process

The cleaner performs these operations in sequence:
1. Removes class/ID attributes
2. Standardizes inline styles
3. Formats lists with consistent spacing
4. Normalizes list item whitespace
5. Formats paragraphs with proper margins
6. Applies custom link styling

## Configuration

### TinyMCE Settings (config/tinymce.php)
```php
return [
    'selector' => '#editor',
    'plugins' => [...],
    'toolbar' => 'undo redo | formatselect | bold italic...',
    'formats' => [
        'bold' => ['inline' => 'strong'],
        'italic' => ['inline' => 'em']
    ]
    // ... other settings
];
```

### Style Standardization
- Font Size Scale:
  - H1: 24px
  - H2: 20px
  - H3: 16px
  - Body: 14px
- Line Height: 1.6
- Link Color: #ff5538
- List Margins: 10px 0
- Paragraph Margins: 0 0 1em 0

## Usage

1. Navigate to the tool in your browser
2. Paste content from Google Docs into the editor
3. Click "Format" to process the content
4. Copy the generated HTML for use in your email newsletter

## Error Handling

The formatter includes:
- Input validation
- Error logging
- Timeout protection
- Malformed HTML recovery

## Browser Compatibility

Tested and working in:
- Chrome
- Firefox
- Safari
- Edge

## Dependencies

- PHP 7.4+
- TinyMCE 6.0+
- Symfony DOM Crawler 3.4+
- Symfony CSS Selector 3.4+
