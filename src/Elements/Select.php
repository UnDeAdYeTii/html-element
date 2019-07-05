<?php

namespace YeTii\HtmlElement\Elements;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;
use YeTii\HtmlElement\Elements\OptGroup;
use YeTii\HtmlElement\Elements\Option;

class Select extends Element
{

    protected $name = 'select';

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
        'autocomplete',
        'autofocus',
        'disabled',
        'form',
        'multiple',
        'name',
        'required',
        'size'
    ];

    public $availableChildren = [
        OptGroup::class,
        Option::class,
    ];

}