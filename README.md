# Mnemonics

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-github-actions]][link-github-actions]
[![Style CI][ico-styleci]][link-styleci]
[![Total Downloads][ico-downloads]][link-downloads]
[![Buy us a tree][ico-treeware-gifting]][link-treeware-gifting]

A mnemonics generator for PHP 7.4+

## Install

Via Composer

```bash
$ composer require owenvoke/mnemonics-php
```

## Usage

**Convert a string to a mnemonic**

```php
use OwenVoke\Mnemonics\Mnemonic;

$mnemonicGenerator = new Mnemonic(DefaultWordList::WORDS);
$mnemonicGenerator->toMnemonic('...');
```
**Convert a mnemonic to a string**

```php
$mnemonicGenerator = new Mnemonic(DefaultWordList::WORDS);
$mnemonicGenerator->toEntropy(['...', '...']);
```

**Using the `make` method**

```php
Mnemonic::make()->toMnemonic('...');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@voke.dev instead of using the issue tracker.

## Credits

- [Owen Voke][link-author]
- [phpninjas][link-phpninjas]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

You're free to use this package, but if it makes it to your production environment you are required to buy the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to plant trees. If you support this package and contribute to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees [here][link-treeware-gifting].

Read more about Treeware at [treeware.earth][link-treeware].

[ico-version]: https://img.shields.io/packagist/v/owenvoke/mnemonics-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-github-actions]: https://img.shields.io/github/workflow/status/owenvoke/mnemonics-php/Tests.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/167410085/shield
[ico-downloads]: https://img.shields.io/packagist/dt/owenvoke/mnemonics-php.svg?style=flat-square
[ico-treeware-gifting]: https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen?style=flat-square

[link-packagist]: https://packagist.org/packages/owenvoke/mnemonics-php
[link-github-actions]: https://github.com/owenvoke/mnemonics-php/actions
[link-styleci]: https://styleci.io/repos/167410085
[link-downloads]: https://packagist.org/packages/owenvoke/mnemonics-php
[link-treeware]: https://treeware.earth
[link-treeware-gifting]: https://ecologi.com/owenvoke?gift-trees
[link-author]: https://github.com/owenvoke
[link-phpninjas]: https://github.com/phpninjas
[link-contributors]: ../../contributors
