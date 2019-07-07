# HTML Element

Provides a more robust alternative to generating HTML

### Requires

- **PHP:** >= 7.2

### Usage

The using is fairly simple. Firstly, you have the different `Element`s under the `YeTii\HtmlElement\Elements` namespace, for example a `<div>` element is `YeTii\HtmlElement\Elements\Div`, an `<input>` element is `YeTii\HtmlElement\Elements\Input`, etc.

**Full List:**

- `<a>`: `A`
- `<abbr>`: `Abbr`
- `<address>`: `Address`
- `<area>`: `Area`
- `<article>`: `Article`
- `<aside>`: `Aside`
- `<audio>`: `Audio`
- `<b>`: `B`
- `<bdi>`: `Bdi`
- `<bdo>`: `Bdo`
- `<blockquote>`: `Blockquote`
- `<body>`: `Body`
- `<br>`: `Br`
- `<button>`: `Button`
- `<canvas>`: `Canvas`
- `<caption>`: `Caption`
- `<cite>`: `Cite`
- `<code>`: `Code`
- `<col>`: `Col`
- `<colgroup>`: `Colgroup`
- `<command>`: `Command`
- `<datalist>`: `Datalist`
- `<dd>`: `Dd`
- `<del>`: `Del`
- `<details>`: `Details`
- `<dfn>`: `Dfn`
- `<div>`: `Div`
- `<dl>`: `Dl`
- `<dt>`: `Dt`
- `<em>`: `Em`
- `<embed>`: `Embed`
- `<fieldset>`: `Fieldset`
- `<figcaption>`: `Figcaption`
- `<figure>`: `Figure`
- `<footer>`: `Footer`
- `<form>`: `Form`
- `<h1>`: `H1`
- `<h2>`: `H2`
- `<h3>`: `H3`
- `<h4>`: `H4`
- `<h5>`: `H5`
- `<h6>`: `H6`
- `<head>`: `Head`
- `<header>`: `Header`
- `<hr>`: `Hr`
- `<html>`: `Html`
- `<i>`: `I`
- `<iframe>`: `Iframe`
- `<img>`: `Img`
- `<input>`: `Input`
- `<ins>`: `Ins`
- `<kbd>`: `Kbd`
- `<keygen>`: `Keygen`
- `<label>`: `Label`
- `<legend>`: `Legend`
- `<li>`: `Li`
- `<main>`: `Main`
- `<map>`: `Map`
- `<mark>`: `Mark`
- `<menu>`: `Menu`
- `<meter>`: `Meter`
- `<nav>`: `Nav`
- `<object>`: `Object`
- `<ol>`: `Ol`
- `<optgroup>`: `Optgroup`
- `<option>`: `Option`
- `<output>`: `Output`
- `<p>`: `P`
- `<param>`: `Param`
- `<pre>`: `Pre`
- `<progress>`: `Progress`
- `<q>`: `Q`
- `<rp>`: `Rp`
- `<rt>`: `Rt`
- `<ruby>`: `Ruby`
- `<s>`: `S`
- `<samp>`: `Samp`
- `<section>`: `Section`
- `<select>`: `Select`
- `<small>`: `Small`
- `<source>`: `Source`
- `<span>`: `Span`
- `<strong>`: `Strong`
- `<sub>`: `Sub`
- `<summary>`: `Summary`
- `<sup>`: `Sup`
- `<table>`: `Table`
- `<tbody>`: `Tbody`
- `<td>`: `Td`
- `<textarea>`: `Textarea`
- `<tfoot>`: `Tfoot`
- `<th>`: `Th`
- `<thead>`: `Thead`
- `<time>`: `Time`
- `<tr>`: `Tr`
- `<track>`: `Track`
- `<u>`: `U`
- `<ul>`: `Ul`
- `<var>`: `Var`
- `<video>`: `Video`
- `<wbr>`: `Wbr`

**Basic Usage:**

The first argument for the `Element` is an array of attributes (key => value), with the second being an optional (not really used at the moment) name to override the default (which is the class name in lowercase, e.g. `Input` is `input`). This may be removed in future versions, the idea was to be able to support Vue-like component names.

```php
$element = new Input([
    'type' => 'text',
    'id' => 'first_name_field',
    'name' => 'first_name',
]);

$element->render(); // <input type="text" id="first_name_field" name="first_name">
```

**Child Elements:**

You can specify a child by providing a `node` or `nodes` "attribute" with a one or more child elements. Alternatively, you can go: `$parent->addChild($child);` or `$parent->addChildren([$child1, $child2]);` 

```php

$child1 = new Span([
    'class' => 'test',
    'nodes' => [
        new Span([
            'class' => 'span-here',
            'node' => 'what?',
        ]),
        new Bold([
            'title' => 'This is bold',
            'node' => 'Just text here'
        ])
    ]
]);
$child2 = new Span([
    'class' => 'test-node',
    'nodes' => [
        new Span([
            'class' => 'a-class',
            'node' => 'who?',
        ]),
        new Bold([
            'title' => 'Bold text',
            'node' => 'Stuff here'
        ])
    ]
]);

$div = new Div([
    'id' => 'section',
    'class' => 'class-name',
]);

$div->addChildren([
    $child1,
    $child2
]);
```

**Text Nodes:**

If you provide a string as a child element(s), it's automatically converted into a text node element.

Text node `Element`s can only have text child elements, which are contacted together when rendered. You may manually specify a `TextNode` instance:

```php
$text = new \Html\HtmlElement\TextNode([
    'node' => 'This is some text',
]);

$text->render(); // This is some text

$div = new Div([
    'node' => $text
]);

$div->render(); // <div>This is some text</div>

$div = new Div([
    'node' => 'So is this'
]);

$div->render(); // <div>So is this</div>
```

**Things to note:**

The order you specify the attributes correlates to the order they are rendered.

```php
$div = new Div([
    'id' => 'a',
    'title' => 'b',
    'class' => 'c',
    'data-id' => 'd',
]);

$div->render(); // <div id="a" title="b" class="c" data-id="d"></div>

$div = new Div([
    'title' => 'b',
    'data-id' => 'd',
    'class' => 'c',
    'id' => 'a',
]);

$div->render(); // <div title="b" data-id="d" class="c" id="a"></div>
```

**htmlspecialchars:**

You can also specify whether or not to encode html special characters by doing the following:

```php
$div = new Div([
    'node' => '<b>Text</b>',
]);
$div->escapeHtml();

$div->render(); // <div>&lt;b&gt;Text&lt;/b&gt;</div>
```

Currently, inheritance does NOT apply to escaping of HTML, meaning that if the parent `Element` is escaping HTML and you provide a child `Element`, and that child `Element` has a text node with HTML - it will NOT be escaped. You must define whether or not to escape the HTML per `Element`. Take the following as an example:

```php
$child = new Span([
    'node' => '<i>Some text</i>',
]);
$parent = new Div([
    'nodes' => [
        '<b>Some text</b>',
        $child
    ]
]);
$parent->escapeHtml();

$parent->render(); // <div>&lt;b&gt;Text&lt;/b&gt;<i>Some text</i></div>
```

The text nodes that are immediate children are escaped, but the `$child` does not inherit that, so it does not escape the HTML in the text node.

Should you want a child `Element` to NOT escape HTML, but would like the immediate parent to, you may do so by doing the following:

```php
$child1 = new Span([
    'node' => '<i>Some text</i>',
]);
$child1->escapeHtml(false);
$child2 = new Span([
    'node' => '<i>Some text</i>',
]);
$parent = new Div([
    'nodes' => [
        '<b>Some text</b>',
        $child2,
    ]
]);
$parent->escapeHtml(true);

$parent->render(); // <div>&lt;b&gt;Text&lt;/b&gt;<i>Some text</i></div>
```

**Markups:**

Excuse the name "markup" - these are similar to traits, however the need for actual traits is thin at the moment thus this simple categorisation of elements.

There are a few different types of schemas - see `Schema::class`.

- `Schema::SINGLETON` is a schema for singleton elements which have no close tags
  - `<area>`
  - `<base>`
  - `<br>`
  - `<col>`
  - `<command>`
  - `<embed>`
  - `<hr>`
  - `<img>`
  - `<input>`
  - `<keygen>`
  - `<link>`
  - `<meta>`
  - `<param>`
  - `<source>`
  - `<track>`
  - `<wbr>`
- `Schema::TEXT_NODE` is for recognising an `Element` is a text node - currently only applied to `TextNode` class
- `Schema::TEXT_CHILD` is for recognising an `Element` must not contain child `Element`s, only TextNode(s), for example, `Textarea`
- `Schema::ATTRIBUTE` yeah, not sure what I was going to do with this. Ignore it.