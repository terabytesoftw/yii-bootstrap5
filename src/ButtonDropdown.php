<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Bootstrap5;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Factory\Exceptions\InvalidConfigException;
use Yiisoft\Html\Html;

/**
 * ButtonDropdown renders a group or split button dropdown bootstrap component.
 *
 * For example,
 *
 * ```php
 * // a button group using Dropdown widget
 * echo ButtonDropdown::widget()
 *     ->label('Action')
 *     ->dropdown'([
 *         'items' => [
 *             ['label' => 'DropdownA', 'url' => '/'],
 *             ['label' => 'DropdownB', 'url' => '#'],
 *         ],
 *     ]);
 * ```
 */
final class ButtonDropdown extends Widget
{
    /**
     * The css class part of dropdown
     */
    public const DIRECTION_DOWN = 'down';

    /**
     * The css class part of dropleft
     */
    public const DIRECTION_LEFT = 'left';

    /**
     * The css class part of dropright
     */
    public const DIRECTION_RIGHT = 'right';

    /**
     * The css class part of dropup
     */
    public const DIRECTION_UP = 'up';

    private string $label = 'Button';
    private array $options = [];
    private array $buttonOptions = [];
    private array $dropdown = [];
    private string $direction = self::DIRECTION_DOWN;
    private bool $split = false;
    private string $tagName = 'button';
    private bool $encodeLabels = true;
    private bool $encodeTags = false;
    private string $dropdownClass = Dropdown::class;
    private bool $renderContainer = true;

    protected function run(): string
    {
        if (!isset($this->dropdown['items'])) {
            return '';
        }

        /** Set options id to button options id to ensure correct css selector in plugin initialisation */
        if (empty($this->options['id'])) {
            $id = $this->getId();

            $this->options['id'] = "{$id}-button-dropdown";
            $this->buttonOptions['id'] = "{$id}-button";
        }

        if ($this->encodeTags === false) {
            $this->options = array_merge($this->options, ['encode' => false]);
        }

        $html = $this->renderButton() . "\n" . $this->renderDropdown();

        if ($this->renderContainer) {
            /** @psalm-suppress InvalidArgument */
            Html::addCssClass($this->options, ['widget' => 'drop' . $this->direction, 'btn-group']);

            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            $html = Html::tag($tag, $html, $options);
        }

        return $html;
    }

    /**
     * The HTML attributes of the button.
     *
     * {@see Html::renderTagAttributes()} for details on how attributes are being rendered.
     *
     * @param array $value
     *
     * @return $this
     */
    public function withButtonOptions(array $value): self
    {
        $new = clone $this;
        $new->buttonOptions = $value;

        return $new;
    }

    /**
     * The drop-direction of the widget.
     *
     * Possible values are 'left', 'right', 'up', or 'down' (default)
     *
     * @param string $value
     *
     * @return $this
     */
    public function withDirection(string $value): self
    {
        $new = clone $this;
        $new->direction = $value;

        return $new;
    }

    /**
     * The configuration array for example:
     *
     * ```php
     *    [
     *        'items' => [
     *            ['label' => 'DropdownA', 'url' => '/'],
     *            ['label' => 'DropdownB', 'url' => '#'],
     *        ],
     *    ]
     * ```
     *
     * {@see Dropdown}
     *
     * @param array $value
     *
     * @return $this
     */
    public function withDropdown(array $value): self
    {
        $new = clone $this;
        $new->dropdown = $value;

        return $new;
    }

    /**
     * Name of a class to use for rendering dropdowns withing this widget. Defaults to {@see Dropdown}.
     *
     * @param string $value
     *
     * @return $this
     */
    public function withDropdownClass(string $value): self
    {
        $new = clone $this;
        $new->dropdownClass = $value;

        return $new;
    }

    /**
     * Whether the label should be HTML-encoded.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function withoutEncodeLabels(bool $value = false): self
    {
        $new = clone $this;
        $new->encodeLabels = $value;

        return $new;
    }

    /**
     * The button label.
     *
     * @param string $value
     *
     * @return $this
     */
    public function withLabel(string $value): self
    {
        $new = clone $this;
        $new->label = $value;

        return $new;
    }

    /**
     * The HTML attributes for the container tag. The following special options are recognized.
     *
     * {@see Html::renderTagAttributes()} for details on how attributes are being rendered.
     *
     * @param array $value
     *
     * @return $this
     */
    public function withOptions(array $value): self
    {
        $new = clone $this;
        $new->options = $value;

        return $new;
    }

    /**
     * Whether to render the container using the {@see options} as HTML attributes. If set to `false`, the container
     * element enclosing the button and dropdown will NOT be rendered.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function withoutRenderContainer(bool $value = false): self
    {
        $new = clone $this;
        $new->renderContainer = $value;

        return $new;
    }

    /**
     * Whether to display a group of split-styled button group.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function withSplit(bool $value = true): self
    {
        $new = clone $this;
        $new->split = $value;

        return $new;
    }

    /**
     * The tag to use to render the button.
     *
     * @param string $value
     *
     * @return $this
     */
    public function withTagName(string $value): self
    {
        $new = clone $this;
        $new->tagName = $value;

        return $new;
    }

    /**
     * Allows you to enable or disable the encoding tags html.
     *
     * @param bool $value
     *
     * @return self
     */
    public function withEncodeTags(bool $value = true): self
    {
        $new = clone $this;
        $new->encodeTags = $value;

        return $new;
    }

    /**
     * Generates the button dropdown.
     *
     * @throws InvalidConfigException
     *
     * @return string the rendering result.
     */
    private function renderButton(): string
    {
        Html::addCssClass($this->buttonOptions, ['buttonOptions' => 'btn']);

        $label = $this->label;

        if ($this->encodeLabels !== false) {
            $label = Html::encode($label);
        }

        if ($this->split) {
            $buttonOptions = $this->buttonOptions;

            $this->buttonOptions['data-bs-toggle'] = 'dropdown';
            $this->buttonOptions['aria-haspopup'] = 'true';
            $this->buttonOptions['aria-expanded'] = 'false';

            Html::addCssClass($this->buttonOptions, ['toggle' => 'dropdown-toggle dropdown-toggle-split']);

            unset($buttonOptions['id']);

            $splitButton = Button::widget()
                ->withLabel('<span class="sr-only">Toggle Dropdown</span>')
                ->withoutEncodeLabels()
                ->withOptions($this->buttonOptions)
                ->render();
        } else {
            $buttonOptions = $this->buttonOptions;

            Html::addCssClass($buttonOptions, ['toggle' => 'dropdown-toggle']);

            $buttonOptions['data-bs-toggle'] = 'dropdown';
            $buttonOptions['aria-haspopup'] = 'true';
            $buttonOptions['aria-expanded'] = 'false';
            $splitButton = '';
        }

        if (!isset($buttonOptions['href']) && ($this->tagName === 'a')) {
            $buttonOptions['href'] = '#';
            $buttonOptions['role'] = 'button';
        }

        return Button::widget()
            ->withTagName($this->tagName)
            ->withLabel($label)
            ->withOptions($buttonOptions)
            ->withoutEncodeLabels()
            ->render()
            . "\n" . $splitButton;
    }

    /**
     * Generates the dropdown menu.
     *
     * @return string the rendering result.
     */
    private function renderDropdown(): string
    {
        $dropdownClass = $this->dropdownClass;

        return $dropdownClass::widget()
            ->withItems($this->dropdown['items'])
            ->withoutEncodeLabels($this->encodeLabels)
            ->render();
    }
}
