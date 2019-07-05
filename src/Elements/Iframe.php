<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;

class Iframe extends Element
{

    protected $name = 'iframe';

    protected $markup = [
        
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
        'allow',
        'csp',
        'height',
        'importance',
        'loading',
        'name',
        'referrerpolicy',
        'sandbox',
        'src',
        'srcdoc',
        'width'
    ];

}