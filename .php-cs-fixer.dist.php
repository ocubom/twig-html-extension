<?php

declare(strict_types=1);

/*
 * This file is part of ocubom/twig-html-extension
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$header = <<<'EOF'
This file is part of ocubom/twig-html-extension

© Oscar Cubo Medina <https://ocubom.github.io>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

if (!file_exists(__DIR__.'/src') && !file_exists(__DIR__.'/tests')) {
    exit(0);
}

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in([
                __DIR__.'/src',
                __DIR__.'/tests',
            ])
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->ignoreVCSIgnored(true)
            //->append([__FILE__])
    )
    ->setRules([
        'header_comment' => ['header' => $header, 'separate' => 'both'],
    ])
    ->setCacheFile(tempnam(sys_get_temp_dir(), 'php-cs-fixer'))
;
