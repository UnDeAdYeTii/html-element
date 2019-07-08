<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;

class HtmlOption extends Element
{
    protected $name = 'option';

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
        'disabled',
        'selected',
        'value',
    ];
}
