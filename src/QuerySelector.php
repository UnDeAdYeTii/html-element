<?php

namespace YeTii\HtmlElement;

class QuerySelector
{

    /**
     * The parent node where all selectors look within
     *
     * @var Element
     */
    protected $parent;

    /**
     * List of query selectors
     *
     * @var array
     */
    protected $selectors = [];

    /**
     * List of matched Elements from the query selector
     *
     * @var array<int,Element>
     */
    protected $matched = [];

    /**
     * Using an Element, create an instance of Query Selector
     * within its scope
     *
     * @param Element $parent
     */
    public function __construct(Element $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Initialse the selectors array
     *
     * @param string $selector
     * @return array
     */
    private function initSelectors(string $selector): array
    {
        $this->selectors = [];

        $selectors = preg_split('/(?!\B"[^"]*),(?![^"]*"\B)/', $selector, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($selectors as $sel) {
            $sel = preg_replace('/\s\s+/', ' ', $sel);
            $sel = preg_replace('/ (\+|\~) /', '$1', $sel);
            $sel = str_replace(' > ', ' >', $sel);
            $sel = trim($sel);

            if (!empty($sel)) {
                $this->selectors[] = $sel;
            }
        }

        return $this->selectors;
    }

    /**
     * Find all elements matching the selector string
     *
     * @param string $selector
     * @return self
     */
    public function find(string $selector): self
    {
        $this->matched = [];
        $this->initSelectors($selector);

        if (empty($this->selectors)) {
            return null;
        }

        foreach ($this->selectors as $selector) {
            $selector = $this->splitSelector($selector);

            $this->findSelector($selector, $this->parent);
        }

        return $this;
    }

    /**
     * Covnert a query selector string into an array of selector rules
     *
     * @param string $selector
     * @return array
     */
    private function splitSelector(string $selector): array
    {
        $parts = [];

        foreach (explode(' ', $selector) as $sel) {
            $sel = trim($sel);
            if (!empty($sel)) {
                $parts[] = $sel;
            }
        }

        return $parts;
    }

    /**
     * With an array of selector segments, find all matched items in $parent,
     * and add all matched items to the $matched property
     *
     * @param array<int,String> $selectors
     * @param Element $parent
     * @return void
     */
    public function findSelector(array $selectors, Element $parent): void
    {
        $found = [];

        // Get the next segment in the list of selector segments
        $toFind = array_shift($selectors);

        // Is this the last one?
        $isFinal = empty($selectors);

        // Retrieve list of elements that match this segment
        $found = $this->findInElement($toFind, $parent);

        // If it's the final selector segment, add the $found items to matched array
        if ($isFinal && $found) {
            foreach ($found as $item) {
                if (!in_array($item, $this->matched)) {
                    $this->matched[] = $item;
                }
            }
        } elseif ($found) {
            // If not final but elements were found
            foreach ($found as $item) {
                // Each one will need to parsed for the next selector segment
                $this->findSelector($selectors, $item);
            }
        }
        
        // Else none found
    }

    /**
     * Recursively (if applicable) match a selector segment $toFind in
     * the $parent Element.
     *
     * @param string  $toFind
     * @param Element $parent
     * @param int     $depth
     * @return array
     */
    public function findInElement(string $toFind, Element $parent, int $depth = 1): array
    {
        $found = [];

        // If the selector starts with a GT symbol then it's a direct child
        $directChild = substr($toFind, 0, 1) === '>';

        if ($directChild) {
            // Since it's asking for a direct child, we don't need to recursively look
            $toFind = substr($toFind, 1);

            // So just iterate the direct children and perform a matched against them
            foreach ($parent->getChildren() as $child) {
                if ($this->matchesSelector($toFind, $child)) {
                    $found[] = $child;
                }
            }
        } else {
            // If the selector has a ~ or + in it
            if (strpos($toFind, '~') || strpos($toFind, '+')) {
                $m = null;
                // Check if it's not in quotes (i.e. attribute selector)
                if (preg_match('/(?!\B"[^"]*)(\~|\+)(?![^"]*"\B)/', $toFind, $m)) {
                    // Retrieve the parts
                    $parts = preg_split('/(?!\B"[^"]*)(\~|\+)(?![^"]*"\B)/', $toFind, -1, PREG_SPLIT_NO_EMPTY);

                    if (count($parts) == 2) {
                        $sibling1Selector = $parts[0];
                        $sibling2Selector = $parts[1];

                        if ($m[1] == '~') {
                            $children = $parent->getChildren();

                            foreach ($children as $child) {
                                if ($this->matchesSelector($sibling1Selector, $child)) {
                                    foreach ($children as $sibling) {
                                        if ($sibling->getIndex() > $child->getIndex()) {
                                            if ($this->matchesSelector($sibling2Selector, $sibling)) {
                                                $found[] = $sibling;
                                            }
                                        }
                                    }
                                }
                            }
                        } else { // $m[1] == '+'
                            $children = $parent->getChildren();

                            foreach ($children as $child) {
                                if ($this->matchesSelector($sibling1Selector, $child)) {
                                    $sibling = $children[$child->getIndex() + 1] ?? null;
                                    if ($sibling !== null && $this->matchesSelector($sibling2Selector, $sibling)) {
                                        $found[] = $sibling;
                                    }
                                }
                            }
                        }

                    } else {
                        throw new \Exception('Invalid CSS selector: `' . $toFind . '`');
                    }
                }
            }

            // Since it's asking for any child (incl recursive children), we'll firstly match the parent element
            if ($depth > 1 && $this->matchesSelector($toFind, $parent)) {
                $found[] = $parent;
            }

            // Then recursively find all child elements
            foreach ($parent->getChildren() as $child) {
                foreach ($this->findInElement($toFind, $child, $depth + 1) as $item) {
                    $found[] = $item;
                }
            }
        }

        // Return what was found
        return $found;
    }

    /**
     * Does the $find selector match this $element
     *
     * @param string  $find
     * @param Element $element
     * @return boolean
     */
    public function matchesSelector(string $find, Element $element): bool
    {
        // Match universal selector
        if ($find === '*') {
            return true;
        }

        $name = $element->getName();

        $ch1 = substr($find, 0, 1);

        if ($ch1 === '#') { // Match ID selector
            return $this->matchesSelector('[id="' . substr($find, 1) . '"]', $element);
        } elseif ($ch1 === '.') { // Match Class selector
            return $this->matchesClass(substr($find, 1), $element);
        } elseif ($ch1 === ':') { // Match psuedo selectors
            // 
        } elseif ($ch1 === '[') { // Match attribute selectors
            $name = $find;
            $value = null;
            $condition = null;

            $pos = strpos($find, '=');
            if ($pos !== false) {
                $value = substr($find, $pos + 1);
                $name = substr($find, 0, $pos);
                $condition = substr($name, -1);
                if (!in_array($condition, ['*','^','$'])) {
                    $condition = null;
                }
            }

            $value = trim($value, ']"');
            $name = trim($name, '[]=$^*');

            return $this->matchesElementAttribute($name, $value, $condition, $element);
        }

        if ($find === $name) {
            return true;
        }

        // TODO: Add more functionality for pseudo and attribute selectors
        return false;
    }

    /**
     * Match an element to an attribute, similar to CSS rules like '[href^=""]'
     *
     * @param string $name
     * @param string $value
     * @param string $condition
     * @param Element $element
     * @return boolean
     */
    public function matchesElementAttribute(string $name, string $value = null, string $condition = null, Element $element): bool
    {
        $attributes = $element->getAttributes([ $name ]);

        if (empty($attributes)) {
            return false;
        }

        if ($value === null) {
            return true;
        }

        $attribute = current($attributes);

        if ($condition == '^') {
            return substr($attribute, 0, strlen($value)) == $value;
        } elseif ($condition == '$') {
            return substr($attribute, 0 - strlen($value)) == $value;
        } elseif ($condition == '*') {
            return strpos($attribute, $value) !== false;
        }

        return $attribute == $value;
    }

    /**
     * Match an element with a class
     *
     * @param string $find
     * @param Element $element
     * @return boolean
     */
    public function matchesClass(string $find, Element $element): bool
    {
        $classes = explode(' ', (string) $element->getAttribute('class'));

        return in_array($find, $classes);
    }

    /**
     * Retrieve the first matched Element
     *
     * @return Element
     */
    public function first(): Element
    {
        return $this->matched[0] ?? null;
    }

    /**
     * Retrieve the last matched Element
     *
     * @return Element
     */
    public function last(): Element
    {
        return $this->matched[count($this->matched) - 1] ?? null;
    }

    /**
     * For each matched item, run a callback
     *
     * @param callable $callback
     * @return void
     */
    public function each(callable $callback): void
    {
        foreach ($this->matched as $element) {
            $callback($element);
        }
    }

    /**
     * Retrieve all matched elements
     *
     * @return array<int,Element>
     */
    public function get(): array
    {
        return $this->matched;
    }

    /**
     * Retrieve the count of matched elements
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->matched);
    }
}
