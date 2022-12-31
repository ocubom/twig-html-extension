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
    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }

    public function getExtensions()
    {
        return [
            new HtmlExtension(),
        ];
    }

    public function getRuntimeLoaders()
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

    public function getTests($name, $legacyTests = false)
    {
        return array_reduce(
            parent::getTests($name, $legacyTests),
            function ($tests, $test) {
                // Change key to be more descriptive
                $tests[sprintf('[% 3d] %s', count($tests), $test[1])] = $test;

                return $tests;
            },
            []
        );
    }
}
