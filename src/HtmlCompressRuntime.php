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

use Ocubom\Twig\Extension\Exception\InvalidArgumentException;
use Ocubom\Twig\Extension\Exception\RuntimeException;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;
use WyriHaximus\HtmlCompress\Factory;
use WyriHaximus\HtmlCompress\HtmlCompressorInterface;

class HtmlCompressRuntime implements RuntimeExtensionInterface
{
    // Factory compression levels
    public const COMPRESSOR_NONE = 0;
    public const COMPRESSOR_FASTEST = 1;
    public const COMPRESSOR_NORMAL = 2;
    public const COMPRESSOR_SMALLEST = 3;

    public const FACTORIES = [
        // By COMPRESSOR_*
        null,
        [Factory::class, 'constructfastest'],
        [Factory::class, 'construct'],
        [Factory::class, 'constructsmallest'],
        // By sort name
        '' => null,
        'none' => null,
        'fastest' => [Factory::class, 'constructfastest'],
        'normal' => [Factory::class, 'construct'],
        'smallest' => [Factory::class, 'constructsmallest'],
    ];

    private bool $force;

    private ?HtmlCompressorInterface $compressor = null;

    /**
     * Constructor
     *
     * @param bool $force Always apply compression
     * @param HtmlCompressorInterface|int|callable $compressor The compressor level, a compressor instance or factory callable
     */
    public function __construct(bool $force = false, $compressor = self::COMPRESSOR_SMALLEST)
    {
        $this->force = $force;

        if ($compressor instanceof HtmlCompressorInterface) {
            $this->compressor = $compressor;
        } else {
            $factory = self::createFactory($compressor);

            if (is_callable($factory)) {
                $this->compressor = $factory();
            } else {
                throw new InvalidArgumentException(sprintf('Unable to build "%s" HTML compressor ', $compressor));
            }
        }
    }

    public function __invoke(Environment $twig, string $html, bool $force = false): string
    {
        if (null === $this->compressor || ($twig->isDebug() && !$this->force && !$force)) {
            // Do nothing as no compressor is configured or no compression is requested
            return $html;
        }

        $html = $this->compressor->compress($html);
        if ($twig->isDebug() && class_exists(WebProfilerBundle::class)) {
            // Add end body tag to allow profiler inject its code
            $html .= '</body>';  // @codeCoverageIgnore
        }

        return $html;
    }

    private static function createFactory($compressor)
    {
        if (is_callable($compressor)) {
            return $compressor;
        }

        if (is_string($compressor)) {
            $compressor = strtolower($compressor);

            if (0 === strpos($compressor, 'construct')) {
                $compressor = substr($compressor, 9);
            }
        }

        if (array_key_exists($compressor, self::FACTORIES)) {
            return self::FACTORIES[$compressor] ?? function () {
                return null;
            };
        }

        return [Factory::class, 'construct' . $compressor];
    }
}
