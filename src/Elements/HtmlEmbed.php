<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Interfaces\IsSingleton;

class HtmlEmbed extends Element implements IsSingleton
{
    protected $name = 'embed';

    protected $availableAttributes = [
        'accesskey',
        'autocapitalize',
        'class',
        'contenteditable',
        'contextmenu',
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
        'height',
        'src',
        'type',
        'width',
    ];
}
