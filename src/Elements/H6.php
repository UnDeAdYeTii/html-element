<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;

class H6 extends Element
{
    protected $name = 'h6';

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
    ];
}
