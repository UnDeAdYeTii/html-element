<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Schema;

class TextNode extends Element
{

    /**
     * @inheritDoc
     */
    protected $name = 'TEXT NODE';

    /**
     * @inheritDoc
     */
    protected $markup = [
        Schema::TEXT_NODE
    ];

    /**
     * @inheritDoc
     */
    public function set(array $args): Element
    {
        foreach ($args as $arg) {
            $this->children[] = $arg;
        }

        return $this;
    }
}
