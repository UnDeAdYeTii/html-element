<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;

class HtmlCanvas extends Element
{
    protected $name = 'canvas';

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
        'width',
    ];
}
