# Regexp::Trie in PHP

[![CircleCI](https://circleci.com/gh/sters/php-regexp-trie.svg?style=svg)](https://circleci.com/gh/sters/php-regexp-trie)
[![Packagist](https://img.shields.io/packagist/v/sters/php-regexp-trie.svg)](https://packagist.org/packages/sters/regexp-trie)

See also the original [Regexp::Trie in Perl](https://metacpan.org/pod/Regexp::Trie).

Transform from [https://github.com/gfx/ruby-regexp_trie](https://github.com/gfx/ruby-regexp_trie).


## Installation

Install Plugin using composer.

```
$ composer require "sters/regexp-trie:dev-master"
```

## Usage

```
use RegexpTrie\RegexpTrie;


$regexpTrie = RegexpTrie::union([
    'foo',
    'bar',
    'baz',
]);

var_dump($regexpTrie->toRegexp()); // string(16) "/(?:foo|ba[rz])/"
```
