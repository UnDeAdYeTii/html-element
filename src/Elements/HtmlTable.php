<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;

class HtmlTable extends Element
{
    protected $name = 'table';

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
        'align',
        'background',
        'bgcolor',
        'border',
        'summary',
    ];
}
