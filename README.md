# Mnemonics

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Style CI][ico-styleci]][link-styleci]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A mnemonics generator for PHP 7.4+

## Install

Via Composer

```bash
$ composer require owenvoke/mnemonics-php
```

## Usage

**Convert a string to a mnemonic**

```php
$mnemonicGenerator = new Mnemonic(DefaultWordList::WORDS);
$mnemonicGenerator->toMnemonic('...');
```
**Convert a mnemonic to a string**

```php
$mnemonicGenerator = new Mnemonic(DefaultWordList::WORDS);
$mnemonicGenerator->toEntropy(['...', '...']);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE_OF_CONDUCT](.github/CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@voke.dev instead of using the issue tracker.

## Credits

- [Owen Voke][link-author]
- [phpninjas][link-phpninjas]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/owenvoke/mnemonics-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/owenvoke/mnemonics-php/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/167410085/shield
[ico-code-quality]: https://img.shields.io/codecov/c/github/owenvoke/mnemonics-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/owenvoke/mnemonics-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/owenvoke/mnemonics-php
[link-travis]: https://travis-ci.com/owenvoke/mnemonics-php
[link-styleci]: https://styleci.io/repos/167410085
[link-code-quality]: https://codecov.io/gh/owenvoke/mnemonics-php
[link-downloads]: https://packagist.org/packages/owenvoke/mnemonics-php
[link-author]: https://github.com/owenvoke
[link-phpninjas]: https://github.com/phpninjas
[link-contributors]: ../../contributors
