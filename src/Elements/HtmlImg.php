<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Interfaces\IsSingleton;

class HtmlImg extends Element implements IsSingleton
{
    protected $name = 'img';

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
        'alt',
        'border',
        'crossorigin',
        'decoding',
        'height',
        'importance',
        'intrinsicsize',
        'ismap',
        'loading',
        'referrerpolicy',
        'sizes',
        'src',
        'srcset',
        'usemap',
        'width',
    ];
}
