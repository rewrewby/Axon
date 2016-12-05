# Axon

[![Build Status](https://travis-ci.org/kleiram/Axon.svg?branch=master)](https://travis-ci.org/kleiram/Axon)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kleiram/Axon/badges/quality-score.png?s=8441141c26d504c5f74522e06ee266889d61e47f)](https://scrutinizer-ci.com/g/kleiram/Axon/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/02520173-4779-49a9-b6b1-a31f51e04f55/mini.png)](https://insight.sensiolabs.com/projects/02520173-4779-49a9-b6b1-a31f51e04f55)

Axon is a library for searching various torrent tracker sites for torrents using
a simple API.

## Installation

Installation is really simple when using [Composer](http://getcomposer.org):

```json
{
    "require": {
        "kleiram/axon": "dev-master"
    }
}
```

And you're all set!

## Usage

The following code is an example of how to use Axon:

```php
// Create a new Axon instance
$axon = new Axon\Search();

// Add a couple of providers to the stack
$axon->registerProvider(new Axon\Search\Provider\ExtraTorrentProvider());
$axon->registerProvider(new Axon\Search\Provider\PirateBayProvider());
$axon->registerProvider(new Axon\Search\Provider\PirateBayProvider(null, 'pirateproxy.vip'));

// Start searching!
$torrents = $axon->search('Iron Man 3');
```

Torrents are automatically filtered (by hash) so duplicate search results are
very rare.

Check the [`lib/Axon/Search/Providers`](https://github.com/kleiram/axon/tree/master/lib/Axon/Search/Providers)
directory for more providers.

### Supported providers

Currently, the following tracker sites are supported:

 - [Extra Torrent](https://github.com/kleiram/axon/blob/master/lib/Axon/Search/Provider/ExtraTorrentProvider.php)
 - [The Pirate Bay](https://github.com/kleiram/axon/blob/master/lib/Axon/Search/Provider/PirateBayProvider.php)
 - And working on more!

## Changelog

```
Version     Changes

0.1.0       - Initial release
```

## License

```
Copyright (c) 2014, Ramon Kleiss <ramonkleiss@gmail.com>
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those
of the authors and should not be interpreted as representing official policies,
either expressed or implied, of the FreeBSD Project.
```
