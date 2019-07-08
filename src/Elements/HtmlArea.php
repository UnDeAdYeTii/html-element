<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Interfaces\IsSingleton;

class HtmlArea extends Element implements IsSingleton
{
    protected $name = 'area';

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
        'target',
    ];
}
