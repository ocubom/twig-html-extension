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

use Twig\Extension\RuntimeExtensionInterface;

class HtmlAttributesRuntime implements RuntimeExtensionInterface
{
    // HTML attributes sorting criteria
    public const SORT_NONE = 'none';             // Do not sort
    public const SORT_DEFAULT = 'strcasecmp';    // Use default sorting method
    public const SORT_NATURAL = 'strnatcasecmp'; // Use natural case for sorting
    public const SORT_SPECIAL = 'no-*';          // Use natural case and consider no-class as class

    /**
     * Sanitize HTML attributes option.
     *
     * @param string          $text      The input string to process
     * @param string|callable $sort      The criteria used to sort elements
     * @param string          $separator The separator between elements
     * @param string          $split     The regular expression used to extract elements
     * @param string          $prefix    The prefix used to negate the element (used with SORT_SPECIAL)
     */
    public function __invoke(
        string $text,
               $sort = self::SORT_SPECIAL,
        string $separator = ' ',
        string $split = '@\s+@s',
        string $prefix = 'no-'
    ): string {
        $values = array_reduce(
            preg_split($split ?? '@\s+@s', $text),
            function ($values, $value) {
                $val = trim($value);
                $values[$val] = $val;

                return $values;
            },
            []
        );

        switch ($sort) {
            case self::SORT_NONE:
            case 'none':
                // Do not sort
                break;

            case self::SORT_DEFAULT:
            case 'default':
                uasort($values, 'strcasecmp');
                break;

            case self::SORT_NATURAL:
            case 'natural':
                uasort($values, 'strnatcasecmp');
                break;

            case self::SORT_SPECIAL:
            case 'special':
                $length = mb_strlen($prefix);
                uasort($values, function ($x, $y) use ($prefix, $length) {
                    if (!strncasecmp($prefix, $x, $length)) {
                        $x = mb_substr($x, $length).' '.$prefix;
                    }
                    if (!strncasecmp($prefix, $y, $length)) {
                        $y = mb_substr($y, $length).' '.$prefix;
                    }

                    return strnatcasecmp($x, $y);
                });
                break;

            default:
                if (\is_callable($sort)) {
                    uasort($values, $sort);
                }
                break;
        }

        return trim(
            implode($separator ?? ' ', $values),
            " \t\n\r\0\x0B".$separator
        );
    }
}
