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

use Ocubom\Twig\Extension\Exception\InvalidArgumentException;
use Ocubom\Twig\Extension\HtmlCompressRuntime;
use PHPUnit\Framework\TestCase;
use Twig\Extension\RuntimeExtensionInterface;
use WyriHaximus\HtmlCompress\Factory;

class HtmlCompressRuntimeTest extends TestCase
{
    public function testCreateWithCompressorInstance()
    {
        $runtime = new HtmlCompressRuntime(false, Factory::construct());

        $this->assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    public function testCreateWithNoneCompressor()
    {
        $runtime = new HtmlCompressRuntime(false, 'none');

        $this->assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    public function testCreateWithConstructor()
    {
        $runtime = new HtmlCompressRuntime(false, 'constructNone');

        $this->assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    public function testCreateWithCallable()
    {
        $runtime = new HtmlCompressRuntime(false, function () {
            return Factory::construct();
        });

        $this->assertInstanceOf(RuntimeExtensionInterface::class, $runtime);
    }

    public function testCreateWithInvalidCompressor()
    {
        $this->expectException(InvalidArgumentException::class);
        $runtime = new HtmlCompressRuntime(false, 'do not exists');

        $this->assertNull($runtime);
    }
}
