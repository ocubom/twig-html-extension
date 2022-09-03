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
    public const SORT_NONE = 'none';    // Do not sort
    public const SORT_DEFAULT = 'default'; // Use default sorting method
    public const SORT_NATURAL = 'natural'; // Use natural case for sorting
    public const SORT_SPECIAL = 'special'; // Use natural case and consider no-class as class

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
        $values = preg_split($split, $text);
        $values = array_combine($values, $values);

        switch ($sort) {
            case self::SORT_NONE:
                // Do not sort
                break;

            case self::SORT_DEFAULT:
                uasort($values, 'strcasecmp');
                break;

            case self::SORT_NATURAL:
                uasort($values, 'strnatcasecmp');
                break;

            case self::SORT_SPECIAL:
                $length = mb_strlen($prefix);
                $values = array_combine(
                    array_map(
                        function (string $item) use ($prefix, $length) {
                            return strncasecmp($prefix, $item, $length)
                                ? $item
                                : mb_substr($item, $length).' '.$prefix;
                        },
                        $values
                    ),
                    $values
                );

                uksort($values, 'strnatcasecmp');
                break;

            default:
                if (\is_callable($sort)) {
                    uasort($values, $sort);
                }
                break;
        }

        return trim(
            implode($separator, $values),
            " \t\n\r\0\x0B".$separator
        );
    }
}
