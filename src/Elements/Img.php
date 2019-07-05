<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;

class Img extends Element
{

    protected $name = 'img';

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
        'width'
    ];

}