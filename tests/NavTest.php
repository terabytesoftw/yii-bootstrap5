<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Bootstrap5\Tests;

use RuntimeException;
use Yiisoft\Yii\Bootstrap5\Dropdown;
use Yiisoft\Yii\Bootstrap5\Nav;

/**
 * Tests for Nav widget.
 *
 * NavTest
 */
final class NavTest extends TestCase
{
    public function testRender(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                    'disabled' => true,
                ],
                [
                    'label' => 'Dropdown1',
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2'],
                        ['label' => 'Page3', 'content' => 'Page3', 'visible' => true],
                    ],
                ],
                [
                    'label' => 'Dropdown2',
                    'visible' => false,
                    'items' => [
                        ['label' => 'Page4', 'content' => 'Page4'],
                        ['label' => 'Page5', 'content' => 'Page5'],
                    ],
                ],
                '<li class="dropdown-divider"></li>',
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Page1</a></li>
<li class="dropdown nav-item"><a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Dropdown1</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
<li><h6 class="dropdown-header">Page2</h6></li>
<li><h6 class="dropdown-header">Page3</h6></li>
</ul></li>
<li class="dropdown-divider"></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testMissingLabel(): void
    {
        Nav::counter(0);

        $this->expectException(RuntimeException::class);
        $html = Nav::widget()->withItems([['content' => 'Page1']])->render();
    }

    public function testRenderDropdownWithDropdownOptions(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
                [
                    'label' => 'Dropdown1',
                    'dropdownOptions' => ['class' => 'test', 'data-id' => 't1', 'id' => 'test1'],
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2'],
                        ['label' => 'Page3', 'content' => 'Page3'],
                    ],
                ],
                [
                    'label' => 'Dropdown2',
                    'visible' => false,
                    'items' => [
                        ['label' => 'Page4', 'content' => 'Page4'],
                        ['label' => 'Page5', 'content' => 'Page5'],
                    ],
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">Page1</a></li>
<li class="dropdown nav-item"><a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Dropdown1</a><ul id="test1" class="test dropdown-menu" aria-expanded="false" data-id="t1">
<li><h6 class="dropdown-header">Page2</h6></li>
<li><h6 class="dropdown-header">Page3</h6></li>
</ul></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testEmptyItems(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => 'Page1',
                    'items' => null,
                ],
                [
                    'label' => 'Dropdown1',
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2'],
                        ['label' => 'Page3', 'content' => 'Page3'],
                    ],
                ],
                [
                    'label' => 'Page4',
                    'items' => [],
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">Page1</a></li>
<li class="dropdown nav-item"><a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Dropdown1</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
<li><h6 class="dropdown-header">Page2</h6></li>
<li><h6 class="dropdown-header">Page3</h6></li>
</ul></li>
<li class="nav-item"><a class="nav-link" href="#">Page4</a></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap/issues/162
     */
    public function testExplicitActive(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withActivateItems(false)
            ->withItems([
                [
                    'label' => 'Item1',
                    'active' => true,
                ],
                [
                    'label' => 'Item2',
                    'url' => '/site/index',
                ],
            ])
            ->render();

        $expected = <<<EXPECTED
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">Item1</a></li>
<li class="nav-item"><a class="nav-link" href="/site/index">Item2</a></li></ul>
EXPECTED;

        $this->assertEqualsWithoutLE($expected, $html);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap/issues/162
     */
    public function testImplicitActive(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withCurrentPath('/site/index')
            ->withItems([
                [
                    'label' => 'Item1',
                    'active' => true,
                ],
                [
                    'label' => 'Item2',
                    'url' => '/site/index',
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link active" href="#">Item1</a></li>
<li class="nav-item"><a class="nav-link active" href="/site/index">Item2</a></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap/issues/162
     */
    public function testExplicitActiveSubitems(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withActivateItems(false)
            ->withCurrentPath('/site/index')
            ->withItems([
                [
                    'label' => 'Item1',
                ],
                [
                    'label' => 'Item2',
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2', 'url' => 'site/index'],
                        ['label' => 'Page3', 'content' => 'Page3', 'active' => true],
                    ],
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">Item1</a></li>
<li class="dropdown nav-item"><a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Item2</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
<li><a class="dropdown-item" href="site/index">Page2</a></li>
<li><h6 class="dropdown-header">Page3</h6></li>
</ul></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap/issues/162
     */
    public function testImplicitActiveSubitems(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => 'Item1',
                ],
                [
                    'label' => 'Item2',
                    'items' => [
                        ['label' => 'Page2', 'content' => 'Page2', 'url' => '/site/index'],
                        ['label' => 'Page3', 'content' => 'Page3', 'active' => true],
                    ],
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">Item1</a></li>
<li class="dropdown nav-item"><a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Item2</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
<li><a class="dropdown-item" href="/site/index">Page2</a></li>
<li><h6 class="dropdown-header">Page3</h6></li>
</ul></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    /**
     * @see https://github.com/yiisoft/yii2-bootstrap/issues/96
     * @see https://github.com/yiisoft/yii2-bootstrap/issues/157
     */
    public function testDeepActivateParents(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withActivateParents(true)
            ->withItems([
                [
                    'label' => 'Dropdown',
                    'items' => [
                        [
                            'label' => 'Sub-dropdown',
                            'items' => [
                                ['label' => 'Page', 'content' => 'Page', 'active' => true],
                            ],
                        ],
                    ],
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="dropdown nav-item"><a class="dropdown-toggle nav-link active" href="#" data-bs-toggle="dropdown">Dropdown</a><ul id="w1-dropdown" class="dropdown-menu" aria-expanded="false">
<li><a class="dropdown-item dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">Sub-dropdown</a><ul class="dropdown active" aria-expanded="false"><ul id="w2-dropdown" class="dropdown-menu" aria-expanded="false">
<li><h6 class="dropdown-header">Page</h6></li>
</ul></ul></li>
</ul></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testEncodeLabel(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => '<span><i class=fas fas-test></i>Page1</span>',
                    'content' => 'Page1',
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">&lt;span&gt;&lt;i class=fas fas-test&gt;&lt;/i&gt;Page1&lt;/span&gt;</a></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => '<span><i class=fas fas-test></i>Page1</span>',
                    'content' => 'Page1',
                ],
            ])
            ->withoutEncodeLabels()
            ->render();
        $expected = <<< HTML
<ul id="w1-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#"><span><i class=fas fas-test></i>Page1</span></a></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testDropdownClass(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
            ])
            ->withDropdownClass(Dropdown::class)
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav"><li class="nav-item"><a class="nav-link" href="#">Page1</a></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testOptions(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withItems([
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
            ])
            ->withOptions(['class' => 'text-link'])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="text-link nav"><li class="nav-item"><a class="nav-link" href="#">Page1</a></li></ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }

    public function testEncodeTags(): void
    {
        Nav::counter(0);

        $html = Nav::widget()
            ->withEncodeTags()
            ->withItems([
                [
                    'label' => 'Page1',
                    'content' => 'Page1',
                ],
            ])
            ->render();
        $expected = <<< HTML
<ul id="w0-nav" class="nav">&lt;li class="nav-item"&gt;&amp;lt;a class="nav-link" href="#"&amp;gt;Page1&amp;lt;/a&amp;gt;&lt;/li&gt;</ul>
HTML;
        $this->assertEqualsWithoutLE($expected, $html);
    }
}
