<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;

class HtmlTd extends Element
{
    protected $name = 'td';

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
        'colspan',
        'headers',
        'rowspan',
    ];
}
