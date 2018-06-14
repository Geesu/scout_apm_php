# Scout APM PHP

[![Latest Version](https://img.shields.io/github/release/thephpleague/skeleton.svg?style=flat-square)](https://github.com/thephpleague/skeleton/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Install

Via Composer

``` bash
$ composer require geesu/scout_apm_php
```

## Usage

``` php
use Scout\Request;

$request = new Request();

$request->startRequest();
$request->startSpan('Controller\MyOperation');

echo "Hello World";

$request->stopSpan();
$request->finishRequest();

```

## Credits

- [Geesu](https://github.com/geesu)
- [All Contributors](https://github.com/thephpleague/scout_apm_php/contributors)
- [Scout](https://github.com/scoutapp/core-agent-api/blob/master/examples/php/HelloWorld.php)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
