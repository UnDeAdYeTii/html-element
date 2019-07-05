<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;

class TextNode extends Element
{
    protected $name = 'TEXT NODE';

    protected $markup = [
        Schema::TEXT_NODE
    ];

    public function set(array $args)
    {
        foreach ($args as $arg) {
            $this->children[] = $arg;
        }
    }
}
