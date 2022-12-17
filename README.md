<div align="center">

Ocubom Twig HTML Extension
==========================

A custom suite of Twig filters for HTML manipulation

[![Contributors][contributors-img]][contributors-url]
[![Forks][forks-img]][forks-url]
[![Stargazers][stars-img]][stars-url]
[![Issues][issues-img]][issues-url]
[![License][license-img]][license-url]

[![Version][packagist-img]][packagist-url]
[![CI][workflow-ci-img]][workflow-ci-url]
[![Code Quality][quality-img]][quality-url]
[![Coverage][coverage-img]][coverage-url]

[**Explore the docs »**](https://github.com/ocubom/twig-html-extension)

[Report Bug](https://github.com/ocubom/twig-html-extension/issues)
·
[Request Feature](https://github.com/ocubom/twig-html-extension/issues)

</div>

<details>
<summary>Contents</summary>

* [About TwigHtmlExtension](#about-twightmlextension)
* [Getting Started](#getting-started)
    * [Installation](#installation)
    * [Usage](#usage)
* [Roadmap](#roadmap)
* [Contributing](#contributing)
* [Authorship](#authorship)
* [License](#license)

</details>

## About TwigHtmlExtension

[TwigHtmlExtension](https://github.com/ocubom/twig-html-extension) is a custom suite of **[Twig filters]** for HTML manipulation.

This suite started as an internal class based on [nochso/html-compress-twig][] to allow the use of [wyrihaximus/html-compress][] with Twig 3.0.
This class used to be embedded into several projects.
Over time, each project adapted its version slightly, leading to fragmented development and difficult maintenance.
Therefore, the development is unified in this extension which is made public in case it is useful for other projects.

## Getting Started

### Installation

Just use [composer][] to add the dependency:

```console
composer require ocubom/twig-html-extension
```

Or add the dependency manually:

1.  Update ``composer.json`` file with the lines:

    ```json
    {
        "require": {
            "ocubom/twig-html-extension": "^1.0.0"
        }
    }
    ```

2.  And update the dependencies:

    ```console
    composer update "ocubom/twig-html-extension"
    ```

### Usage

Just register the Twig extension:

```php
$twig = new \Twig\Environment();
$twig->addExtension(new \Ocubom\Twig\Extension\HtmlExtension());
$thig->addRuntimeLoader(use Twig\RuntimeLoader\FactoryRuntimeLoader([
    \Ocubom\Twig\Extension\HtmlAttributesRuntime::class => function() {
        return new \Ocubom\Twig\Extension\HtmlAttributesRuntime();
    },
    \Ocubom\Twig\Extension\HtmlCompressRuntime::class => function() {
        return new \Ocubom\Twig\Extension\HtmlCompressRuntime();
    },
]));

// You can also dynamically create a RuntimeLoader 
$twig->addRuntimeLoader(new class() implements RuntimeLoaderInterface {
    public function load($class)
    {
        if (\Ocubom\Twig\Extension\HtmlAttributesRuntime::class === $class) {
            return new \Ocubom\Twig\Extension\HtmlAttributesRuntime();
        }
        
        if (\Ocubom\Twig\Extension\HtmlCompressRuntime::class === $class) {
            return new \Ocubom\Twig\Extension\HtmlCompressRuntime();
        }
        
        return null;
    }
});
```

_For more examples, please refer to the [Documentation](https://github.com/ocubom/twig-html-extension)._

## Roadmap

See the [open issues](https://github.com/ocubom/twig-html-extension/issues) for a full list of proposed features (and known issues).

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create.
Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request.
You can also simply open an issue with the tag "enhancement".

1. Fork the Project.
2. Create your Feature Branch (`git checkout -b feature/your-feature`).
3. Commit your Changes (`git commit -m 'Add your-feature'`).
4. Push to the Branch (`git push origin feature/your-feature`).
5. Open a Pull Request.

## Authorship

* Oscar Cubo Medina — [@ocubom](https://twitter.com/ocubom) — https://ocubom.github.io

See also the list of [contributors][contributors-url] who participated in this project.

## License

Distributed under the MIT License.
See [LICENSE][] for more information.


[LICENSE]: https://github.com/ocubom/twig-html-extension/blob/master/LICENSE

<!-- Links -->
[composer]: https://getcomposer.org/
[Symfony]: https://symfony.com/
[Twig filters]: https://twig.symfony.com/doc/3.x/advanced.html#filters

<!-- Packagist links -->
[nochso/html-compress-twig]: https://packagist.org/packages/nochso/html-compress-twig
[wyrihaximus/html-compress]: https://packagist.org/packages/wyrihaximus/html-compress

<!-- Project Badges -->
[contributors-img]: https://img.shields.io/github/contributors/ocubom/twig-html-extension.svg?style=for-the-badge
[contributors-url]: https://github.com/ocubom/twig-html-extension/graphs/contributors
[forks-img]:        https://img.shields.io/github/forks/ocubom/twig-html-extension.svg?style=for-the-badge
[forks-url]:        https://github.com/ocubom/twig-html-extension/network/members
[stars-img]:        https://img.shields.io/github/stars/ocubom/twig-html-extension.svg?style=for-the-badge
[stars-url]:        https://github.com/ocubom/twig-html-extension/stargazers
[issues-img]:       https://img.shields.io/github/issues/ocubom/twig-html-extension.svg?style=for-the-badge
[issues-url]:       https://github.com/ocubom/twig-html-extension/issues
[license-img]:      https://img.shields.io/github/license/ocubom/twig-html-extension.svg?style=for-the-badge
[license-url]:      https://github.com/ocubom/twig-html-extension/blob/master/LICENSE
[workflow-ci-img]:  https://img.shields.io/github/actions/workflow/status/ocubom/twig-html-extension/test.yml?branch=main&label=CI&logo=github&style=for-the-badge
[workflow-ci-url]:  https://github.com/ocubom/twig-html-extension/actions/
[packagist-img]:    https://img.shields.io/packagist/v/ocubom/twig-html-extension.svg?logo=packagist&logoColor=%23fefefe&style=for-the-badge
[packagist-url]:    https://packagist.org/packages/ocubom/twig-html-extension
[coverage-img]:     https://img.shields.io/scrutinizer/coverage/g/ocubom/twig-html-extension.svg?logo=scrutinizer&logoColor=fff&style=for-the-badge
[coverage-url]:     https://scrutinizer-ci.com/g/ocubom/twig-html-extension/code-structure/main/code-coverage
[quality-img]:      https://img.shields.io/scrutinizer/quality/g/ocubom/twig-html-extension.svg?logo=scrutinizer&logoColor=fff&style=for-the-badge
[quality-url]:      https://scrutinizer-ci.com/g/ocubom/twig-html-extension/