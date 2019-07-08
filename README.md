# HTML Element

Provides a more robust alternative to generating HTML

### Requires

- **PHP:** >= 7.2

### Usage

The using is fairly simple. Firstly, you have the different `Element`s under the `YeTii\HtmlElement\Elements` namespace, for example a `<div>` element is `YeTii\HtmlElement\Elements\HtmlDiv`, an `<input>` element is `YeTii\HtmlElement\Elements\HtmlInput`, etc. This is except for the `YeTii\HtmlElement\TextNode`. See below for full list of classes.

**Basic Usage:**

The first argument for the `Element` is an array of attributes (key => value).

The second being an optional (not really used at the moment) name to override the default name. The default name is the class name in lowercase, stripped of the `Html` prefix, for example `HtmlInput` is rendered using the name `input`). This argument may be removed in future versions, the idea was to be able to support Vue-like component names. Alternatively you can use `$element->setName($name);` to set a name (and `$element->getName();` to retrieve it)

```php
$element = new HtmlInput([
    'type' => 'date',
    'id' => 'dob_field',
    'name' => 'dob',
]);

$element->render(); // <input type="date" id="dob_field" name="dob">

// Or try a custom element name (if generating Vue, for example)
$element->setName('dob-picker');

$element->render(); // <dob-picker type="date" id="dob_field" name="dob">
```

**Child Elements:**

You can specify a child by providing a `node` or `nodes` "attribute" as a quickhand for adding a child node or multiple children nodes. Alternatively, you can go: `$parent->addChild(Element $child);` or `$parent->addChildren(array $children);` 

```php
$child1 = new HtmlSpan([
    'class' => 'test',
    'nodes' => [
        new HtmlSpan([
            'class' => 'span-here',
            'node' => 'what?',
        ]),
        new HtmlBold([
            'title' => 'This is bold',
            'node' => 'Just text here'
        ])
    ]
]);

$child2 = new HtmlSpan([
    'class' => 'test-node',
    'nodes' => [
        new HtmlSpan([
            'class' => 'a-class',
            'node' => 'who?',
        ])
    ]
]);
$child2->addChild(new HtmlBold([
    'title' => 'Bold text',
    'node' => 'Stuff here'
]));

$div = new HtmlDiv([
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

$div = new HtmlDiv([
    'node' => $text
]);

$div->render(); // <div>This is some text</div>

$div = new HtmlDiv([
    'node' => 'So is this'
]);

$div->render(); // <div>So is this</div>
```

**Things to note:**

The order you specify the attributes correlates to the order they are rendered.

```php
$div = new HtmlDiv([
    'id' => 'a',
    'title' => 'b',
    'class' => 'c',
    'data-id' => 'd',
]);

$div->render(); // <div id="a" title="b" class="c" data-id="d"></div>

$div = new HtmlDiv([
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
$div = new HtmlDiv([
    'node' => '<b>Text</b>',
]);
$div->escapeHtml();

$div->render(); // <div>&lt;b&gt;Text&lt;/b&gt;</div>
```

Currently, inheritance does NOT apply to escaping of HTML, meaning that if the parent `Element` is escaping HTML and you provide a child `Element`, and that child `Element` has a text node with HTML - it will NOT be escaped. You must define whether or not to escape the HTML per `Element`. Take the following as an example:

```php
$child = new HtmlSpan([
    'node' => '<i>Some text</i>',
]);
$parent = new HtmlDiv([
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
$child1 = new HtmlSpan([
    'node' => '<i>Some text</i>',
]);
$child1->escapeHtml(false);
$child2 = new HtmlSpan([
    'node' => '<i>Some text</i>',
]);
$parent = new HtmlDiv([
    'nodes' => [
        '<b>Some text</b>',
        $child2,
    ]
]);
$parent->escapeHtml(true);

$parent->render(); // <div>&lt;b&gt;Text&lt;/b&gt;<i>Some text</i></div>
```

**Full list of Element classes:**

- `<a>`: `HtmlA`
- `<abbr>`: `HtmlAbbr`
- `<address>`: `HtmlAddress`
- `<area>`: `HtmlArea`
- `<article>`: `HtmlArticle`
- `<aside>`: `HtmlAside`
- `<audio>`: `HtmlAudio`
- `<b>`: `HtmlB`
- `<bdi>`: `HtmlBdi`
- `<bdo>`: `HtmlBdo`
- `<blockquote>`: `HtmlBlockquote`
- `<body>`: `HtmlBody`
- `<br>`: `HtmlBr`
- `<button>`: `HtmlButton`
- `<canvas>`: `HtmlCanvas`
- `<caption>`: `HtmlCaption`
- `<cite>`: `HtmlCite`
- `<code>`: `HtmlCode`
- `<col>`: `HtmlCol`
- `<colgroup>`: `HtmlColgroup`
- `<command>`: `HtmlCommand`
- `<datalist>`: `HtmlDatalist`
- `<dd>`: `HtmlDd`
- `<del>`: `HtmlDel`
- `<details>`: `HtmlDetails`
- `<dfn>`: `HtmlDfn`
- `<div>`: `HtmlDiv`
- `<dl>`: `HtmlDl`
- `<dt>`: `HtmlDt`
- `<em>`: `HtmlEm`
- `<embed>`: `HtmlEmbed`
- `<fieldset>`: `HtmlFieldset`
- `<figcaption>`: `HtmlFigcaption`
- `<figure>`: `HtmlFigure`
- `<footer>`: `HtmlFooter`
- `<form>`: `HtmlForm`
- `<h1>`: `HtmlH1`
- `<h2>`: `HtmlH2`
- `<h3>`: `HtmlH3`
- `<h4>`: `HtmlH4`
- `<h5>`: `HtmlH5`
- `<h6>`: `HtmlH6`
- `<head>`: `HtmlHead`
- `<header>`: `HtmlHeader`
- `<hr>`: `HtmlHr`
- `<html>`: `HtmlHtml`
- `<i>`: `HtmlI`
- `<iframe>`: `HtmlIframe`
- `<img>`: `HtmlImg`
- `<input>`: `HtmlInput`
- `<ins>`: `HtmlIns`
- `<kbd>`: `HtmlKbd`
- `<keygen>`: `HtmlKeygen`
- `<label>`: `HtmlLabel`
- `<legend>`: `HtmlLegend`
- `<li>`: `HtmlLi`
- `<main>`: `HtmlMain`
- `<map>`: `HtmlMap`
- `<mark>`: `HtmlMark`
- `<menu>`: `HtmlMenu`
- `<meter>`: `HtmlMeter`
- `<nav>`: `HtmlNav`
- `<object>`: `HtmlObject`
- `<ol>`: `HtmlOl`
- `<optgroup>`: `HtmlOptgroup`
- `<option>`: `HtmlOption`
- `<output>`: `HtmlOutput`
- `<p>`: `HtmlP`
- `<param>`: `HtmlParam`
- `<pre>`: `HtmlPre`
- `<progress>`: `HtmlProgress`
- `<q>`: `HtmlQ`
- `<rp>`: `HtmlRp`
- `<rt>`: `HtmlRt`
- `<ruby>`: `HtmlRuby`
- `<s>`: `HtmlS`
- `<samp>`: `HtmlSamp`
- `<section>`: `HtmlSection`
- `<select>`: `HtmlSelect`
- `<small>`: `HtmlSmall`
- `<source>`: `HtmlSource`
- `<span>`: `HtmlSpan`
- `<strong>`: `HtmlStrong`
- `<sub>`: `HtmlSub`
- `<summary>`: `HtmlSummary`
- `<sup>`: `HtmlSup`
- `<table>`: `HtmlTable`
- `<tbody>`: `HtmlTbody`
- `<td>`: `HtmlTd`
- `<textarea>`: `HtmlTextarea`
- `<tfoot>`: `HtmlTfoot`
- `<th>`: `HtmlTh`
- `<thead>`: `HtmlThead`
- `<time>`: `HtmlTime`
- `<tr>`: `HtmlTr`
- `<track>`: `HtmlTrack`
- `<u>`: `HtmlU`
- `<ul>`: `HtmlUl`
- `<var>`: `HtmlVar`
- `<video>`: `HtmlVideo`
- `<wbr>`: `HtmlWbr`

**Interfaces:**

These interfaces change the way an element is rendered.

- `YeTii\HtmlElement\Interfaces\HasTextChild` renders an element with children as text-only (e.g. `<textarea>text child here</textarea>`)
- `YeTii\HtmlElement\Interfaces\IsSingleton` renders an element with no closing tag (e.g. `<input />`)
- `YeTii\HtmlElement\Interfaces\IsTextNode` renders an element as a text node (i.e. raw or htmlspecialchar'd text)

**Custom Elements:**

You may want to create some custom elements, especially if generating something like vue component templates. Using the interfaces above, and extending the base `Element` class, it becomes a very easy task. Say you want an singleton element like `<dob-picker type="date" {customAttributesHere} />`

```php
<?php

namespace YeTii\VueGenerator\Component;

use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\Interfaces\IsSingleton;

class DobPicker extends Element implements IsSingleton
{

    protected $name = 'dob-picker';

    protected $attributes = [
        'type' => 'date', // all <dob-pickers> should have this
    ];

}

```