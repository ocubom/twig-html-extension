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
use Ocubom\Twig\Extension\Html\Exception\Throwable;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;
use WyriHaximus\HtmlCompress\Factory;
use WyriHaximus\HtmlCompress\HtmlCompressorInterface;

class HtmlCompressRuntime implements RuntimeExtensionInterface
{
    public const DEFAULT_COMPRESSOR = self::COMPRESSOR_SMALLEST;

    // Factory compression levels
    public const COMPRESSOR_NONE = 0;
    public const COMPRESSOR_FASTEST = 1;
    public const COMPRESSOR_NORMAL = 2;
    public const COMPRESSOR_SMALLEST = 3;

    private const FACTORIES = [
        // By COMPRESSOR_*
        self::COMPRESSOR_NONE => null,
        self::COMPRESSOR_FASTEST => [Factory::class, 'constructFastest'],
        self::COMPRESSOR_NORMAL => [Factory::class, 'construct'],
        self::COMPRESSOR_SMALLEST => [Factory::class, 'constructSmallest'],
        // By name
        '' => null,
        'none' => null,
        'fastest' => [Factory::class, 'constructFastest'],
        'normal' => [Factory::class, 'construct'],
        'smallest' => [Factory::class, 'constructSmallest'],
        // Booleans
        true => [Factory::class, 'constructSmallest'],
        false => null,
    ];

    private bool $force;

    private ?HtmlCompressorInterface $compressor = null;

    /**
     * Constructor.
     *
     * @param bool                                                  $force      Always apply compression
     * @param HtmlCompressorInterface|callable|int|string|bool|null $compressor The compressor level, a compressor instance or factory callable
     */
    public function __construct(bool $force = false, $compressor = self::DEFAULT_COMPRESSOR)
    {
        try {
            $this->force = $force;

            if (null === $compressor) {
                $this->compressor = null;
            } elseif ($compressor instanceof HtmlCompressorInterface) {
                $this->compressor = $compressor;
            } elseif (is_callable($compressor)) {
                $this->compressor = $compressor();
            } else {
                assert(is_scalar($compressor));
                $factory = self::normalizeCompressorName($compressor);
                assert(is_scalar($factory));
                if (array_key_exists($factory, self::FACTORIES)) {
                    $factory = self::FACTORIES[$factory];

                    $this->compressor = is_callable($factory) ? $factory() : $factory;
                } else {
                    $factory = [Factory::class, 'construct'.($factory ?? '-')];

                    $this->compressor = $factory();
                }
            }
        } catch (Throwable $exc) {
            throw $exc;
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

    public function __invoke(Environment $twig, string $html, bool $force = false): string
    {
        if (null !== $this->compressor && (!$twig->isDebug() || $this->force || $force)) {
            // Compress
            $html = $this->compressor->compress($html);

            // Add end body tag to allow profiler inject its code
            if ($twig->isDebug() && class_exists(WebProfilerBundle::class) && preg_match('/<body(\s|>)/i', $html)) {
                $html .= '</body>';
            }
        }

        return $html;
    }

    /**
     * @param int|string|bool $value
     *
     * @return int|string|bool
     */
    private static function normalizeCompressorName($value)
    {
        // Integers in range
        if (is_int($value) || (is_string($value) && ctype_digit($value))) {
            return max(self::COMPRESSOR_NONE, min(self::COMPRESSOR_SMALLEST, (int) $value));
        }

        // Normalized string
        if (is_string($value)) {
            // Normalize key
            $value = strtolower($value);

            // Remove "construct" prefix
            if (0 === strncmp('construct', $value, 9)) {
                return substr($value, 9);
            }

            return $value;
        }

        // Other values
        return (bool) $value;
    }
}
