<?php

function dump()
{
    foreach (func_get_args() as $arg) {
        if (php_sapi_name() === 'cli') {
            echo print_r($arg, true).PHP_EOL;
        } else {
            echo '<pre>'.htmlspecialchars(print_r($arg, true)).'</pre>';
        }
    }
}

function dd()
{
    call_user_func_array('dump', func_get_args());
    die();
}

require_once 'vendor/autoload.php';

// $el = new YeTii\HtmlElement\Elements\Input([
//     'name' => 'name',
//     'id' => 'name_field',
//     'value' => 'Bob',
// ]);

$el = new YeTii\HtmlElement\Elements\Select([
    'name' => 'organisation_id',
    'id' => 'org_field',
    'nodes' => [
        new YeTii\HtmlElement\Elements\Option([
            'value' => 1,
            'node' => 'AB Web',
            'selected' => null,
        ]),
        new YeTii\HtmlElement\Elements\Option([
            'value' => 2,
            'node' => 'Test Organisation',
            'selected' => true,
        ]),
        new YeTii\HtmlElement\Elements\Option([
            'value' => 3,
            'node' => 'Google',
            'selected' => null,
        ]),
    ],
]);

// $el = new YeTii\HtmlElement\Elements\Textarea([
//     'name' => 'comments',
//     'id' => 'comments_field',
//     'node' => 'This is the value',
// ]);

echo htmlspecialchars($el->render());

echo '<pre>';
print_r($el);

die();
$dir = 'src/Elements/';

$singletons = [
    'area',
    'base',
    'br',
    'col',
    'command',
    'embed',
    'hr',
    'img',
    'input',
    'keygen',
    'link',
    'meta',
    'param',
    'source',
    'track',
    'wbr',
];

$attributesForElements = [
    'form' => ['accept', 'accept-charset', 'action', 'autocomplete', 'enctype', 'method', 'name', 'novalidate', 'target'],
    'input' => ['accept', 'alt', 'autocomplete', 'autofocus', 'checked', 'dirname', 'disabled', 'form', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget', 'height', 'list', 'max', 'maxlength', 'minlength', 'min', 'multiple', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'size', 'src', 'step', 'type', 'usemap', 'value', 'width'],
    'applet' => ['align', 'alt', 'code', 'codebase'],
    'caption' => ['align'],
    'col' => ['align', 'bgcolor', 'span'],
    'colgroup' => ['align', 'bgcolor', 'span'],
    'hr' => ['align', 'color'],
    'iframe' => ['align', 'allow', 'csp', 'height', 'importance', 'loading', 'name', 'referrerpolicy', 'sandbox', 'src', 'srcdoc', 'width'],
    'img' => ['align', 'alt', 'border', 'crossorigin', 'decoding', 'height', 'importance', 'intrinsicsize', 'ismap', 'loading', 'referrerpolicy', 'sizes', 'src', 'srcset', 'usemap', 'width'],
    'table' => ['align', 'background', 'bgcolor', 'border', 'summary'],
    'tbody' => ['align', 'bgcolor'],
    'td' => ['align', 'background', 'bgcolor', 'colspan', 'headers', 'rowspan'],
    'tfoot' => ['align', 'bgcolor'],
    'th' => ['align', 'background', 'bgcolor', 'colspan', 'headers', 'rowspan', 'scope'],
    'thead' => ['align'],
    'tr' => ['align', 'bgcolor'],
    'area' => ['alt', 'coords', 'download', 'href', 'hreflang', 'media', 'ping', 'referrerpolicy', 'rel', 'shape', 'target'],
    'script' => ['async', 'charset', 'crossorigin', 'defer', 'importance', 'integrity', 'language', 'referrerpolicy', 'src', 'type'],
    'select' => ['autocomplete', 'autofocus', 'disabled', 'form', 'multiple', 'name', 'required', 'size'],
    'textarea' => ['autocomplete', 'autofocus', 'cols', 'dirname', 'disabled', 'enterkeyhint', 'form', 'inputmode', 'maxlength', 'minlength', 'name', 'placeholder', 'readonly', 'required', 'rows', 'wrap'],
    'button' => ['autofocus', 'disabled', 'form', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget', 'name', 'type', 'value'],
    'keygen' => ['autofocus', 'challenge', 'disabled', 'form', 'keytype', 'name'],
    'audio' => ['autoplay', 'buffered', 'controls', 'crossorigin', 'loop', 'muted', 'preload', 'src'],
    'video' => ['autoplay', 'buffered', 'controls', 'crossorigin', 'height', 'loop', 'muted', 'poster', 'preload', 'src', 'width'],
    'body' => ['background', 'bgcolor'],
    'marquee' => ['bgcolor', 'loop'],
    'object' => ['border', 'data', 'form', 'height', 'name', 'type', 'usemap', 'width'],
    'meta' => ['charset', 'content', 'http-equiv', 'name'],
    'command' => ['checked', 'disabled', 'icon', 'radiogroup', 'type'],
    'blockquote' => ['cite'],
    'del' => ['cite', 'datetime'],
    'ins' => ['cite', 'datetime'],
    'q' => ['cite'],
    'basefont' => ['color'],
    'font' => ['color'],
    'link' => ['crossorigin', 'href', 'hreflang', 'importance', 'integrity', 'media', 'referrerpolicy', 'rel', 'sizes'],
    'time' => ['datetime'],
    'track' => ['default', 'kind', 'label', 'src', 'srclang'],
    'fieldset' => ['disabled', 'form', 'name'],
    'optgroup' => ['disabled'],
    'option' => ['disabled', 'selected', 'value'],
    'a' => ['download', 'href', 'hreflang', 'media', 'ping', 'referrerpolicy', 'rel', 'shape', 'target'],
    'label' => ['for', 'form'],
    'output' => ['for', 'form', 'name'],
    'meter' => ['form', 'high', 'low', 'max', 'min', 'optimum', 'value'],
    'progress' => ['form', 'max', 'value'],
    'canvas' => ['height', 'width'],
    'embed' => ['height', 'src', 'type', 'width'],
    'base' => ['href', 'target'],
    'bgsound' => ['loop'],
    'html' => ['manifest'],
    'source' => ['media', 'sizes', 'src', 'srcset', 'type'],
    'style' => ['media', 'scoped', 'type'],
    'map' => ['name'],
    'param' => ['name', 'value'],
    'details' => ['open'],
    'ol' => ['reversed', 'start'],
    'menu' => ['type'],
    'data' => ['value'],
    'li' => ['value'],
];

$globalAttributes = [
    'accesskey',
    'autocapitalize',
    'class',
    'contenteditable',
    'contextmenu',
    'data-*',
    'dir',
    'draggable',
    'dropzone',
    'hidden',
    'id',
    'itemprop',
    'lang',
    'slot',
    'spellcheck',
    'style',
    'tabindex',
    'title',
    'translate',
    'enterkeyhint',
    'inputmode',
];

foreach (scandir($dir) as $file) {
    if ($file[0] == '.') {
        continue;
    }

    $path = $dir.$file;

    $clName = str_replace('.php', '', $file);
    $name = strtolower($clName);

    $specificAttributes = $attributesForElements[$name] ?? [];
    $attributes = array_merge($globalAttributes, $specificAttributes);

    $markup = [];

    if (in_array($name, $singletons)) {
        $markup[] = 'Schema::SINGLETON';
    }

    foreach ($attributes as $key => $attribute) {
        $attributes[$key] = "'{$attribute}'";
    }

    $markup = implode(",\n        ", $markup);
    $attributes = implode(",\n        ", $attributes);

    $contents = <<<EOL
<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;

class {$clName} extends Element
{

    protected \$name = '{$name}';

    protected \$markup = [
        {$markup}
    ];

    protected \$availableAttributes = [
        {$attributes}
    ];

}
EOL;

    file_put_contents($path, $contents);
}
