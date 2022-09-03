<?php

/*
 * This file is part of ocubom/twig-html-extension
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HtmlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'html_attributes',
                [HtmlAttributesRuntime::class, '__invoke'],
                [
                    'is_safe' => ['html'],
                ]
            ),
            new TwigFilter(
                'html_compress',
                [HtmlCompressRuntime::class, '__invoke'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }
}
