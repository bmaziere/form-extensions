<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Form\Tests\Type;

use Sonata\Form\Test\AbstractWidgetTestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @author Christian Gripp <mail@core23.de>
 */
class FormChoiceWidgetTest extends AbstractWidgetTestCase
{
    public function testLabelRendering(): void
    {
        $choices = ['some' => 0, 'choices' => 1];

        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            $this->getDefaultOption() + [
                'multiple' => true,
                'expanded' => true,
            ] + compact('choices')
        );

        $html = $this->renderWidget($choice->createView());

        $this->assertStringContainsString(
            $this->cleanHtmlWhitespace(
                <<<'HTML'
<div id="choice">
    <input type="checkbox" id="choice_0" name="choice[]" value="0" />
    <label for="choice_0">[trans]some[/trans]</label>
    <input type="checkbox" id="choice_1" name="choice[]" value="1" />
    <label for="choice_1">[trans]choices[/trans]</label>
</div>
HTML
            ),
            $this->cleanHtmlWhitespace($html)
        );
    }

    public function testDefaultValueRendering(): void
    {
        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            $this->getDefaultOption()
        );

        $html = $this->renderWidget($choice->createView());

        $this->assertStringContainsString(
            '<option value="" selected="selected">[trans]Choose an option[/trans]</option>',
            $this->cleanHtmlWhitespace($html)
        );
    }

    public function testRequiredIsDisabledForEmptyPlaceholder(): void
    {
        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            $this->getRequiredOption()
        );

        $html = $this->renderWidget($choice->createView());

        $this->assertStringNotContainsString(
            'required="required"',
            $this->cleanHtmlWhitespace($html)
        );
    }

    public function testRequiredIsEnabledIfPlaceholderIsSet(): void
    {
        $choice = $this->factory->create(
            $this->getChoiceClass(),
            null,
            array_merge($this->getRequiredOption(), $this->getDefaultOption())
        );

        $html = $this->renderWidget($choice->createView());

        $this->assertStringContainsString(
            'required="required"',
            $this->cleanHtmlWhitespace($html)
        );
    }

    private function getRequiredOption()
    {
        return [
            'required' => true,
        ];
    }

    private function getChoiceClass()
    {
        return ChoiceType::class;
    }

    private function getDefaultOption()
    {
        return [
            'placeholder' => 'Choose an option',
        ];
    }
}
