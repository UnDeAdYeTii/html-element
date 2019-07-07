<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Exceptions\InvalidAttributeException;
use YeTii\HtmlElement\TextNode;
use YeTii\HtmlElement\Traits\HasTextChild;
use YeTii\HtmlElement\Traits\IsSingleton;
use YeTii\HtmlElement\Traits\IsTextNode;

class Element
{
    
    /**
     * Optional name for the element, should PHP class naming forbid the
     * element name. Defaults to the Element's PHP class name.
     *
     * @var string
     */
    protected $name;

    /**
     * List of current attributes for this Element
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * List of available attributes per Element.
     * Used to prevent invalid attributes from being set.
     *
     * @var array
     */
    protected $availableAttributes = [];

    /**
     * List of Elements in this Element's child nodes
     *
     * @var array
     */
    protected $children = [];

    /**
     * List of attributes which do not require a value. The existence of
     * the attribute means true, while the absense means false
     *
     * @var array
     */
    protected $booleanAttributes = [
        'checked',
        'selected',
        'required',
        'readonly',
        'disabled',
    ];

    /**
     * Indicator whether or not to render text contents as raw or
     * htmlspecialchar'd. Null indicates inheritance, if applicable.
     *
     * @var boolean
     */
    protected $escapeHtml = null;

    /**
     * Create a new Element
     *
     * @param array $args List of attributes (and/or nodes)
     * @param string $name Optional name to set for this element, used for rendering
     */
    public function __construct(array $args = null, string $name = null)
    {
        if ($args !== null) {
            $this->set($args);
        }

        if ($name !== null) {
            $this->setName($name);
        }

        if ($this->name === null) {
            $cl = get_class($this);
            $this->setName(strtolower(substr($cl, strrpos($cl, '\\') + 1)));
        }
    }

    /**
     * Override the name of the element. Used for when rendering the element.
     *
     * @param string $name The name of the element
     * @return Element
     */
    public function setName(string $name): Element
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return the name of the element
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Bulk set attributes (and nodes) for this element
     *
     * @param array $args List of attributes (and/or nodes)
     * @return Element
     */
    public function set(array $args): Element
    {
        foreach ($args as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Set an attribute name and value for this element, or add one or more
     * child elements to this element's child nodes
     *
     * @param string $key The name of the attribute
     * @param mixed $value The value of the attribute
     * @return Element
     */
    public function setAttribute(string $key, $value): Element
    {
        if ($key === 'nodes' || $key === 'node') {
            return $this->addChildren((array) $value);
        }

        if (in_array($key, $this->availableAttributes) || substr($key, 0, 5) === 'data-') {
            $this->attributes[$key] = $value;
            
            return $this;
        }

        throw new InvalidAttributeException('Invalid `' . $this->name . '` attribute name: ' . $key);
    }

    /**
     * Append multiple elements to this element's child nodes
     *
     * @param array $children List of child elements
     * @return Element
     */
    public function addChildren(array $children): Element
    {
        foreach ($children as $child) {
            if (is_string($child)) {
                $child = new TextNode([
                    'node' => $child
                ]);
            }

            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Append an element to this element's child nodes 
     *
     * @param Element $child A child Element
     * @return Element
     */
    public function addChild(Element $child): Element
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Render any given element in its entirety
     *
     * @return string
     */
    public function render(): string
    {
        if ($this->hasTrait(IsTextNode::class)) {
            $html = implode('', $this->children);
            
            if ($this->escapeHtml) {
                $html = htmlspecialchars($html);
            }

            return $html;
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

        if ($this->hasTrait(IsSingleton::class)) {
            $html[] = ' />';
        } else {
            $html[] = '>';

            $html[] = $this->renderChildren();

            $html[] = '</' . $this->name . '>';
        }

        $html = implode('', $html);

        return $html;
    }

    /**
     * Render all child elements of any given element as a string
     *
     * @return string
     */
    public function renderChildren(): string
    {
        $html = [];
        
        foreach ($this->children as $child) {
            $html[] = $child->render();
        }

        $html = implode('', $html);

        if ($this->hasTrait(HasTextChild::class) && $this->escapeHtml) {
            $html = htmlspecialchars($html);
        }

        return $html;
    }

    /**
     * Specify whether or not to render this Element's text nodes
     * are raw or htmlspecialchar'd
     *
     * @param boolean $escape Enable or disable escaping of HTML?
     * @return Element
     */
    public function escapeHtml(bool $escapeHtml = true, $inheritFalse = false): Element
    {
        // If explictly set to false (i.e. not null) and should retain that false
        if ($this->escapeHtml === false && $inheritFalse) {
            // Then don't do anything
            return $this;
        }

        $this->escapeHtml = $escapeHtml;

        // If true
        if ($escapeHtml) {
            // Then find all child text nodes and apply the same logic
            foreach ($this->children as $child) {
                if (is_object($child)) {
                    if ($this->hasTrait(IsTextNode::class)) {
                        // .. unless it's been explicitly set to false
                        $child->escapeHtml(true, true);
                    }
                }
            }
        }

        return $this;
    }

    function hasTrait($trait) {
        return in_array($trait, array_keys(class_uses($this)));
    }
}
