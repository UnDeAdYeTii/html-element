<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Interfaces\IsTextNode;

class TextNode extends Element implements IsTextNode
{

    /**
     * @inheritDoc
     */
    protected $name = '#text';

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
