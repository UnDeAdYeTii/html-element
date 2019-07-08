<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Interfaces\IsTextNode;

class TextNode extends Element implements IsTextNode
{
    /**
     * {@inheritdoc}
     */
    protected $name = '#text';

    /**
     * {@inheritdoc}
     */
    public function set(array $args): Element
    {
        foreach ($args as $arg) {
            $this->children[] = $arg;
        }

        return $this;
    }
}
