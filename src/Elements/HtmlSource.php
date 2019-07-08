<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Interfaces\IsSingleton;

class HtmlSource extends Element implements IsSingleton
{
    protected $name = 'source';

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
        'media',
        'sizes',
        'src',
        'srcset',
        'type',
    ];
}
