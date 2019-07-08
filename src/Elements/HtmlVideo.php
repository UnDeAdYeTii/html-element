<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;

class HtmlVideo extends Element
{
    protected $name = 'video';

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
        'autoplay',
        'buffered',
        'controls',
        'crossorigin',
        'height',
        'loop',
        'muted',
        'poster',
        'preload',
        'src',
        'width',
    ];
}
