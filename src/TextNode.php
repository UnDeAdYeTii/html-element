<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Traits\IsTextNode;

class TextNode extends Element
{

    use IsTextNode;

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
