<?php

namespace YeTii\HtmlElement;

use YeTii\HtmlElement\Interfaces\IsTextNode;
use YeTii\HtmlElement\Interfaces\IsSingleton;
use YeTii\HtmlElement\Interfaces\HasTextChild;
use YeTii\HtmlElement\Exceptions\InvalidAttributeException;

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
     * List of current attributes for this Element.
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
     * List of Elements in this Element's child nodes.
     *
     * @var array
     */
    protected $children = [];

    /**
     * List of attributes which do not require a value. The existence of
     * the attribute means true, while the absense means false.
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
     * @var bool
     */
    protected $escapeHtml = null;

    /**
     * Create a new Element.
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
    }

    /**
     * Override the name of the element. Used for when rendering the element.
     *
     * @param string $name The name of the element
     * @return Element
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return the name of the element.
     *
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            $cl = get_class($this);
            $count = 1;
            $name = str_replace('Html', '', substr($cl, strrpos($cl, '\\') + 1), $count);
            $this->setName(strtolower($name));
        }

        return $this->name;
    }

    /**
     * Bulk set attributes (and nodes) for this element.
     *
     * @param array $args List of attributes (and/or nodes)
     * @return Element
     */
    public function set(array $args): self
    {
        foreach ($args as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Set an attribute name and value for this element, or add one or more
     * child elements to this element's child nodes.
     *
     * @param string $key The name of the attribute
     * @param mixed $value The value of the attribute
     * @return Element
     */
    public function setAttribute(string $key, $value): self
    {
        if ($key === 'nodes' || $key === 'node') {
            if (! is_array($value)) {
                $value = [$value];
            }

            return $this->addChildren($value);
        }

        if (in_array($key, $this->availableAttributes) || substr($key, 0, 5) === 'data-') {
            $this->attributes[$key] = $value;

            return $this;
        }

        throw new InvalidAttributeException('Invalid `'.$this->getName().'` attribute name: '.$key);
    }

    /**
     * Append multiple elements to this element's child nodes.
     *
     * @param array $children List of child elements
     * @return Element
     */
    public function addChildren(array $children): self
    {
        foreach ($children as $child) {
            if (is_string($child)) {
                $child = new TextNode([
                    'node' => $child,
                ]);
            }

            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Append an element to this element's child nodes.
     *
     * @param Element $child A child Element
     * @return Element
     */
    public function addChild(self $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Render any given element in its entirety, escaping all direct child
     * TextNodes with htmlspecialchars to prevent HTML-injection
     *
     * @return string
     */
    public function renderEscaped(): string
    {
        $this->escapeHtml(true);

        return $this->render();
    }

    /**
     * Render any given element in its entirety.
     *
     * @return string
     */
    public function render(): string
    {
        if ($this instanceof IsTextNode) {
            $html = implode('', $this->children);

            if ($this->escapeHtml) {
                $html = htmlspecialchars($html);
            }

            return $html;
        }

        $html = [
            '<'.$this->getName(),
        ];

        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->booleanAttributes)) {
                if (! $value) {
                    continue;
                }

                $html[] = ' '.$key;
                continue;
            }

            if ($value === null) {
                $html[] = ' '.$key;
                continue;
            }

            $value = htmlspecialchars((string) $value);

            $html[] = <<<EOL
 {$key}="{$value}"
EOL;
        }

        if ($this instanceof IsSingleton) {
            $html[] = ' />';
        } else {
            $html[] = '>';

            $html[] = $this->renderChildren();

            $html[] = '</'.$this->getName().'>';
        }

        $html = implode('', $html);

        return $html;
    }

    /**
     * Render all child elements of any given element as a string.
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

        if ($this instanceof HasTextChild && $this->escapeHtml) {
            $html = htmlspecialchars($html);
        }

        return $html;
    }

    /**
     * Specify whether or not to render this Element's text nodes
     * are raw or htmlspecialchar'd.
     *
     * @param  bool  $escapeHtml
     * @param  bool  $inheritFalse
     * @return Element
     */
    public function escapeHtml(bool $escapeHtml = true, $inheritFalse = false): self
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
                    if ($child instanceof IsTextNode) {
                        // .. unless it's been explicitly set to false
                        $child->escapeHtml(true, true);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Dump this element and it's children into a parseable array
     *
     * @param  array $options Options for exporting
     * @return array
     */
    public function toArray(array $options = null): array
    {
        $only = !empty($options['only']) ? (array) $options['only'] : null;

        $element = [
            'name' => $this->getName(),
            'attributes' => $this->getAttributes($only),
        ];

        if (empty($options['children']) || $options['children']) {
            $element['nodes'] = [];
            if ($this instanceof TextNode) {
                $element['nodes'] = $this->children;
            } else {
                foreach ($this->children as $child) {
                    $element['nodes'][] = $child->toArray($options);
                }
            }
        }

        if (!empty($options['metadata']) && $options['metadata']) {
            $element['_escapeHtml'] = $this->escapeHtml;
        }

        return $element;
    }

    /**
     * Return all (or a subset of) the element's attributes
     *
     * @param array $only List of attributes to retrieve. Default: all
     * @return array
     */
    public function getAttributes(array $only = null): array
    {
        if ($only === null || empty($only)) {
            return $this->attributes;
        }

        $attributes = [];
        foreach ($only as $key) {
            $attributes[$key] = $this->attributes[$key] ?? null;
        }

        return $attributes;
    }

    /**
     * Generate an Element instance from an array (such as one exported from `toArray()`)
     *
     * @param array $src
     * @return Element
     */
    public static function fromArray(array $src): Element
    {
        $cl = 'YeTii\HtmlElement\Elements\Html' . ucfirst($src['name']);

        $element = new $cl();
        
        $element->set($src['attributes']);

        if (!empty($src['nodes'])) {
            $children = [];

            foreach ($src['nodes'] as $node) {
                $children[] = self::fromArray($node);
            }
            
            $element->addChildren($children);
        }

        return $element;
    }
}
