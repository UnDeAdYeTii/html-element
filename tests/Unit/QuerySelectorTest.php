<?php

namespace YeTii\HtmlElement\Tests\Unit;

use PHPUnit\Framework\TestCase;
use YeTii\HtmlElement\Elements\HtmlB;
use YeTii\HtmlElement\Elements\HtmlDiv;
use YeTii\HtmlElement\Elements\HtmlInput;
use YeTii\HtmlElement\Elements\HtmlSpan;
use YeTii\HtmlElement\Elements\HtmlTextarea;
use YeTii\HtmlElement\TextNode;
use YeTii\HtmlElement\Element;
use YeTii\HtmlElement\QuerySelector;

final class QuerySelectorTest extends TestCase
{
    /** @test */
    public function itCanMatchAnElementsAttribute(): void
    {
        $div = new HtmlDiv([
            'id' => 'test-div'
        ]);

        $q = new QuerySelector($div);

        $this->assertEquals(true, $q->matchesElementAttribute('id', 'test-div', null, $div));
        $this->assertEquals(false, $q->matchesElementAttribute('id', 'test-di', null, $div));
    }

    /** @test */
    public function itCanMatchAnElementsAttributeStartsWith(): void
    {
        $div = new HtmlDiv([
            'id' => 'test-div'
        ]);

        $q = new QuerySelector($div);

        $this->assertEquals(true, $q->matchesElementAttribute('id', 'test-', '^', $div));
        $this->assertEquals(false, $q->matchesElementAttribute('id', 'est-', '^', $div));
    }

    /** @test */
    public function itCanMatchAnElementsAttributeEndsWith(): void
    {
        $div = new HtmlDiv([
            'id' => 'test-div'
        ]);

        $q = new QuerySelector($div);

        $this->assertEquals(true, $q->matchesElementAttribute('id', '-div', '$', $div));
        $this->assertEquals(false, $q->matchesElementAttribute('id', '-di', '$', $div));
    }

    /** @test */
    public function itCanMatchAnElementsAttributeContains(): void
    {
        $div = new HtmlDiv([
            'id' => 'test-div'
        ]);

        $q = new QuerySelector($div);

        $this->assertEquals(true, $q->matchesElementAttribute('id', 't-d', '*', $div));
        $this->assertEquals(false, $q->matchesElementAttribute('id', 't-f', '*', $div));
    }

    /** @test */
    public function itCanFindAChildUsingASelector(): void
    {
        $child = new HtmlSpan([
            'id' => 'some-span'
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                $child
            ]
        ]);

        $q = new QuerySelector($div);

        $this->assertFalse($q->matchesSelector('span', $div));
        $this->assertTrue($q->matchesSelector('div', $div));
    }

    /** @test */
    public function itCanRetrieveAChildUsingASelector(): void
    {
        $child1 = new HtmlSpan([
            'id' => 'some-span'
        ]);
        $child2 = new HtmlDiv([
            'id' => 'some-div'
        ]);
        $child3 = new HtmlB([
            'id' => 'some-b'
        ]);
        $child4 = new HtmlDiv([
            'id' => 'some-div-2'
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                $child1,
                $child2,
                $child3,
                $child4,
            ]
        ]);

        $q = new QuerySelector($div);

        // Find a single span
        $found = $q->findInElement('span', $div);

        $this->assertCount(1, $found);
        $this->assertEquals($child1, $found[0]);

        // Find all divs
        
        $found = $q->findInElement('div', $div);

        $this->assertCount(2, $found);
        $this->assertEquals($child2, $found[0]);
        $this->assertEquals($child4, $found[1]);

        // Find all where ID contains "-div"
        
        // $found = $q->findInElement('[div*="-div"]', $div);

        // $this->assertCount(2, $found);
        // $this->assertEquals($child2, $found[0]);
        // $this->assertEquals($child4, $found[1]);
        
        // $found = $q->findInElement('[div^="some-div"]', $div);

        // $this->assertCount(2, $found);
        // $this->assertEquals($child2, $found[0]);
        // $this->assertEquals($child4, $found[1]);
        
        // $found = $q->findInElement('[div$="-div"]', $div);

        // $this->assertCount(1, $found);
        // $this->assertEquals($child2, $found[0]);
    }

    /** @test */
    public function itCanRetrieveADirectChildUsingASelector(): void
    {
        $child3 = new HtmlSpan([
            'id' => 'third-level-span',
        ]);

        $child2 = new HtmlSpan([
            'id' => 'some-other-span',
            'nodes' => [
                $child3
            ],
        ]);

        $child1 = new HtmlSpan([
            'id' => 'some-span',
            'nodes' => [
                $child2
            ],
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                $child1,
            ]
        ]);

        $q = new QuerySelector($div);

        // Find a single span
        $found = $q->findInElement('>span', $div);

        $this->assertCount(1, $found);
        $this->assertEquals($child1, $found[0]);

        // Find all spans
        $found = $q->findInElement('span', $div);

        $this->assertCount(3, $found);
        $this->assertEquals($child1, $found[0]);
        $this->assertEquals($child2, $found[1]);
        $this->assertEquals($child3, $found[2]);
    }

    /** @test */
    public function itCanPerformMultipleLevelsOfSelectors(): void
    {
        $child3 = new HtmlSpan([
            'id' => 'third-level-span',
        ]);

        $child2 = new HtmlSpan([
            'id' => 'some-other-span',
            'nodes' => [
                $child3
            ],
        ]);

        $child1 = new HtmlSpan([
            'id' => 'some-span',
            'nodes' => [
                $child2
            ],
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                $child1,
            ]
        ]);

        $q = new QuerySelector($div);

        // Find a single span
        $q->findSelector(['>span', 'span'], $div);

        $this->assertEquals(2, $q->count());
        $this->assertEquals($child2, $q->get()[0]);
        $this->assertEquals($child3, $q->get()[1]);

        $q = new QuerySelector($div);

        $q->findSelector(['[id="some-other-span"]', 'span'], $div);

        $this->assertEquals(1, $q->count());
        $this->assertEquals($child3, $q->first());
    }

    /** @test */
    public function itCanRetrieveTheFirstMatch(): void
    {
        $child3 = new HtmlSpan([
            'id' => 'third-level-span',
        ]);

        $child2 = new HtmlSpan([
            'id' => 'some-other-span',
            'nodes' => [
                $child3
            ],
        ]);

        $child1 = new HtmlSpan([
            'id' => 'some-span',
            'nodes' => [
                $child2
            ],
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                $child1,
            ]
        ]);

        $q = new QuerySelector($div);

        // Find a single span
        $q->findSelector(['>span', 'span'], $div);

        $this->assertEquals(2, $q->count());
        $this->assertEquals($child2, $q->first());
    }

    /** @test */
    public function itCanRetrieveTheLastMatch(): void
    {
        $child3 = new HtmlSpan([
            'id' => 'third-level-span',
        ]);

        $child2 = new HtmlSpan([
            'id' => 'some-other-span',
            'nodes' => [
                $child3
            ],
        ]);

        $child1 = new HtmlSpan([
            'id' => 'some-span',
            'nodes' => [
                $child2
            ],
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                $child1,
            ]
        ]);

        $q = new QuerySelector($div);

        // Find a single span
        $q->findSelector(['>span', 'span'], $div);

        $this->assertEquals(2, $q->count());
        $this->assertEquals($child3, $q->last());
    }

    /** @test */
    public function itCanRetrieveTheNumberOfMatches(): void
    {
        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                new HtmlSpan([
                    'id' => 'some-span1',
                ]),
                new HtmlSpan([
                    'id' => 'some-span2',
                ]),
                new HtmlSpan([
                    'id' => 'some-span3',
                ])
            ]
        ]);

        $q = new QuerySelector($div);

        // Find a single span
        $q->findSelector(['span'], $div);

        $this->assertEquals(3, $q->count());
    }

    /** @test */
    public function itCanCorrectlyPerformAFind(): void
    {
        $child1b = new HtmlSpan([
            'id' => 'some-span1b',
        ]);

        $div = new HtmlDiv([
            'id' => 'test-div',
            'nodes' => [
                new HtmlSpan([
                    'id' => 'some-span1',
                    'nodes' => [
                        $child1b,
                    ],
                ]),
                new HtmlSpan([
                    'id' => 'some-span2',
                ]),
                new HtmlSpan([
                    'id' => 'some-span3',
                ]),
            ],
        ]);

        $q = new QuerySelector($div);

        // Find all spans
        $q->find('span');

        $this->assertEquals(4, $q->count());

        // Find a single span
        $q->find('span > span');

        $this->assertEquals(1, $q->count());
        $this->assertEquals($child1b, $q->first());
    }
}
