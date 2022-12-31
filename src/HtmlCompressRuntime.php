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

use Ocubom\Twig\Extension\Html\Exception\InvalidArgumentException;
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

    private const FACTORIES = [
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
     * Constructor.
     *
     * @param bool                                        $force      Always apply compression
     * @param HtmlCompressorInterface|callable|int|string $compressor The compressor level, a compressor instance or factory callable
     */
    public function __construct(bool $force = false, $compressor = self::COMPRESSOR_SMALLEST)
    {
        $this->force = $force;

        if ($compressor instanceof HtmlCompressorInterface) {
            $this->compressor = $compressor;
        } else {
            try {
                $factory = is_callable($compressor) ? $compressor : self::createFactory($compressor);
                $this->compressor = $factory();
            } catch (\Throwable $exc) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Unable to build "%s" HTML compressor ',
                        is_scalar($compressor) ? $compressor : 'callable',
                    ),
                    0,
                    $exc
                );
            }
        }
    }

    public function __invoke(Environment $twig, string $html, bool $force = false): string
    {
        if (null !== $this->compressor && (!$twig->isDebug() || $this->force || $force)) {
            // Compress
            $html = $this->compressor->compress($html);

            // Add end body tag to allow profiler inject its code
            if ($twig->isDebug() && class_exists(WebProfilerBundle::class)) {
                $html .= '</body>'; // @codeCoverageIgnore
            }
        }

        return $html;
    }

    /**
     * @param int|string $compressor
     */
    private static function createFactory($compressor): callable
    {
        $key = is_string($compressor) ? self::cleanValue($compressor) : $compressor;
        if (array_key_exists($key, self::FACTORIES)) {
            return self::FACTORIES[$key] ?? function () {
                return null;
            };
        }

        return function () use ($key): HtmlCompressorInterface {
            $factory = [Factory::class, 'construct'.$key];

            return $factory();
        };
    }

    private static function cleanValue(string $name): string
    {
        $value = strtolower($name);

        if (str_starts_with($value, 'construct')) {
            $value = substr($value, 9);
        }

        return $value;
    }
}
