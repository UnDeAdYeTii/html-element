<?php
namespace YeTii\HtmlElement\Tests\Unit;

use PHPUnit\Framework\TestCase;
use YeTii\HtmlElement\Elements\B as Bold;
use YeTii\HtmlElement\Elements\Div;
use YeTii\HtmlElement\Elements\Input;
use YeTii\HtmlElement\Elements\Span;
use YeTii\HtmlElement\Elements\Textarea;
use YeTii\HtmlElement\TextNode;

final class ElementsTest extends TestCase
{

    /** @test */
    public function itCanRetrieveTheDefaultName(): void
    {
        $div = new Div();

        $expected = 'div';

        $this->assertEquals($expected, $div->getName());
    }

    /** @test */
    public function itCanRetrieveTheOverridenName(): void
    {
        $div = new Div([], 'divv');

        $expected = 'divv';

        $this->assertEquals($expected, $div->getName());
    }

    /** @test */
    public function itGeneratesHtmlForSingletonElements(): void
    {
        $input = new Input([
            'id' => 'input_id',
            'name' => 'test',
            'value' => '34',
        ]);

        $expected = '<input id="input_id" name="test" value="34" />';

        $this->assertEquals($expected, $input->render());
    }

    /** @test */
    public function itGeneratesHtmlForTextNodeElements(): void
    {
        $el = new Textarea([
            'id' => 'comments_field',
            'name' => 'comments',
            'class' => 'form-control',
            'node' => 'Look at me, I\'m a pickle, Morty',
        ]);

        $expected = '<textarea id="comments_field" name="comments" class="form-control">Look at me, I\'m a pickle, Morty</textarea>';

        $this->assertEquals($expected, $el->render());
    }

    /** @test */
    public function itGeneratesHtmlForNormalElements(): void
    {
        $div = new Div([
            'id' => 'section',
            'class' => 'class-name',
        ]);

        $expected = '<div id="section" class="class-name"></div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function itGeneratesHtmlForNormalElementsWithSingleChildElement(): void
    {
        $child = new Span([
            'class' => 'something',
        ]);

        $div = new Div([
            'id' => 'section',
            'class' => 'class-name',
            'nodes' => [
                $child,
            ],
        ]);

        $expected = '<div id="section" class="class-name"><span class="something"></span></div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function itGeneratesHtmlForNormalElementsWithMultipleChildElement(): void
    {
        $child1 = new Span([
            'class' => 'something',
        ]);
        $child2 = new Bold([
            'class' => 'else',
        ]);

        $div = new Div([
            'id' => 'section',
            'class' => 'class-name',
            'nodes' => [
                $child1,
                $child2,
            ],
        ]);

        $expected = '<div id="section" class="class-name"><span class="something"></span><b class="else"></b></div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function itGeneratesHtmlForNormalElementsWithSingleChildTextNode(): void
    {
        $child = 'This is a test';

        $div = new Div([
            'id' => 'section',
            'class' => 'class-name',
            'node' => $child,
        ]);

        $expected = '<div id="section" class="class-name">This is a test</div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function itGeneratesHtmlForNormalElementsWithMultipleChildTextNodes(): void
    {
        $child1 = 'This is a test.';
        $child2 = 'So is this';

        $div = new Div([
            'id' => 'section',
            'class' => 'class-name',
            'nodes' => [
                $child1,
                $child2,
            ],
        ]);

        $expected = '<div id="section" class="class-name">This is a test.So is this</div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function itGeneratesHtmlForNormalElementsWithMultipleNodesWithMultipleNodes(): void
    {
        $child1 = new Span([
            'class' => 'test',
            'nodes' => [
                new Span([
                    'class' => 'span-here',
                    'node' => 'what?',
                ]),
                new Bold([
                    'title' => 'This is bold',
                    'node' => 'Just text here',
                ]),
            ],
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
                    'node' => 'Stuff here',
                ]),
            ],
        ]);

        $div = new Div([
            'id' => 'section',
            'class' => 'class-name',
            'nodes' => [
                $child1,
                $child2,
            ],
        ]);

        $expected = '<div id="section" class="class-name"><span class="test"><span class="span-here">what?</span><b title="This is bold">Just text here</b></span><span class="test-node"><span class="a-class">who?</span><b title="Bold text">Stuff here</b></span></div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function theOrderOfAttributesDefinedIsToTheOrderOfAttributesRendered(): void
    {
        $div = new Div([
            'id' => 'a',
            'title' => 'b',
            'class' => 'c',
            'data-id' => 'd',
        ]);

        $expected = '<div id="a" title="b" class="c" data-id="d"></div>';

        $this->assertEquals($expected, $div->render());

        // then mix it up
        $div = new Div([
            'title' => 'b',
            'data-id' => 'd',
            'class' => 'c',
            'id' => 'a',
        ]);

        $expected = '<div title="b" data-id="d" class="c" id="a"></div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function htmlSpecialCharsAreNotEncodedForTextNodes(): void
    {
        $div = new Div([
            'node' => '<b>test</b>',
        ]);

        $expected = '<div><b>test</b></div>';

        $this->assertEquals($expected, $div->render());

        // Then try htmlspecialchars

        $div = new Div([
            'node' => htmlspecialchars('<b>test</b>'),
        ]);

        $expected = '<div>&lt;b&gt;test&lt;/b&gt;</div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function htmlSpecialCharsAreEncodedForTextNodesWhenSpecified(): void
    {
        $div = new Div([
            'node' => '<b>test</b>',
        ]);

        $div->escapeHtml();

        $expected = '<div>&lt;b&gt;test&lt;/b&gt;</div>';

        $this->assertEquals($expected, $div->render(1));

        // Then try htmlspecialchars

        $div = new Div([
            'node' => htmlspecialchars('<b>test</b>'),
        ]);

        $div->escapeHtml();

        $expected = '<div>&amp;lt;b&amp;gt;test&amp;lt;/b&amp;gt;</div>';

        $this->assertEquals($expected, $div->render());
    }

    /** @test */
    public function explicitlyDisablingEscapingOfHtmlOnTextNodeIsRespected(): void
    {
        $child2 = new TextNode([
            'node' => '<b>test2</b>',
        ]);
        $child2->escapeHtml(false);

        $div = new Div([
            'nodes' => [
                '<i>test</i>',
                $child2,
            ],
        ]);

        $div->escapeHtml();

        $expected = '<div>&lt;i&gt;test&lt;/i&gt;<b>test2</b></div>';

        $this->assertEquals($expected, $div->render());
    }
}
