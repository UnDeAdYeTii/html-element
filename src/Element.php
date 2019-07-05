<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\TextNode;

class Element
{
    protected $name;

    protected $markup = [];

    protected $attributes = [];

    protected $availableAttributes = [];

    protected $children = [];

    protected $availableChildren = [];

    protected $props = [];

    protected $booleanAttributes = [
        'checked',
        'selected',
        'required',
        'readonly',
        'disabled',
    ];

    public function __construct(array $args = null)
    {
        if ($args !== null) {
            $this->set($args);
        }
    }

    public function set(array $args)
    {
        foreach ($args as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->availableAttributes) || substr($key, 0, 5) === 'data-') {
            return $this->attributes[$key] = $value;
        }

        if ($key === 'nodes' || $key === 'node') {
            return $this->addChildren($value);
        }

        throw new \Exception('Invalid `' . $this->name . '` attribute name: ' . $key);
    }

    public function addChildren($children)
    {
        if (is_string($children)) {
            $children = [
                new TextNode([
                    'node' => $children
                ])
            ];
        }

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function addChild($child)
    {
        $cl = get_class($child);
        
        if (!in_array($cl, $this->availableChildren) && $cl !== 'YeTii\HtmlElement\TextNode') {
            throw new \Exception('Invalid `' . $this->name . '` child class: ' . $cl);
        }
        
        $this->children[] = $child;
    }

    public function render()
    {
        if ($this->isMarkup(Schema::TEXT_NODE)) {
            return implode('', $this->children);
        }

        $html = [
            '<' . $this->name
        ];

        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->booleanAttributes)) {
                if (!$value) {
                    continue;
                }

                $html[] = ' ' . $key;
                continue;
            }

            if ($value === null) {
                $html[] = ' ' . $key;
                continue;
            }

            $value = htmlspecialchars((string) $value);

            $html[] = <<<EOL
 {$key}="{$value}"
EOL;
        }

        if ($this->isMarkup(Schema::SINGLETON)) {
            $html[] = ' />';
        } else {
            $html[] = '>';
        }

        if (!$this->isMarkup(Schema::SINGLETON)) {
            if ($this->isMarkup(Schema::TEXT_CHILD)) {
                $html[] = htmlspecialchars($this->renderChildren());
            } else {
                $html[] = $this->renderChildren();
            }

            $html[] = '</' . $this->name . '>';
        }
        
        $html = implode('', $html);

        return $html;
    }

    public function renderChildren()
    {
        $html = [];
        
        foreach ($this->children as $child) {
            $html[] = $child->render();
        }

        return implode('', $html);
    }

    public function isMarkup($markup)
    {
        return in_array($markup, $this->markup);
    }
}
