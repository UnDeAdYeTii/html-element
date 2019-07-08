<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Schema;
use YeTii\HtmlElement\Element;

class Var extends Element
{
    protected $name = 'var';

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
        'inputmode'
    ];
}
