<?php

/*
 * This file is part of ocubom/twig-html-extension
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Twig\Extension\Tests;

use Ocubom\Twig\Extension\HtmlAttributesRuntime;
use Ocubom\Twig\Extension\HtmlCompressRuntime;
use Ocubom\Twig\Extension\HtmlExtension;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\Test\IntegrationTestCase;

final class HtmlExtensionTest extends IntegrationTestCase
{
    public function getFixturesDir(): string
    {
        return self::getFixturesDirectory();
    }

    public static function getFixturesDirectory(): string
    {
        return __DIR__.'/Fixtures/';
    }

    public function getExtensions(): array
    {
        return [
            new HtmlExtension(),
        ];
    }

    public function getRuntimeLoaders(): array
    {
        return [
            new FactoryRuntimeLoader([
                HtmlAttributesRuntime::class => function () {
                    return new HtmlAttributesRuntime();
                },
                HtmlCompressRuntime::class => function () {
                    return new HtmlCompressRuntime();
                },
            ]),
        ];
    }
}
