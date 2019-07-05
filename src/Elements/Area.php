<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;

class Area extends Element
{

    protected $name = 'area';

    protected $markup = [
        Schema::SINGLETON
    ];

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
        'alt',
        'coords',
        'download',
        'href',
        'hreflang',
        'media',
        'ping',
        'referrerpolicy',
        'rel',
        'shape',
        'target'
    ];

}