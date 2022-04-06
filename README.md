# Task

Task is about to refactor the family simulator.

## Prerequisites/Requirements

- PHP 7.4 or greater

## Installation

Use the [composer](https://getcomposer.org/) to install packages.

```bash
composer install
```
## What I am using

- Using a __Routing__ system allows us to structure our application in a better way instead of designating each request to a file
- __Twig__ is a templating engine that helps you keep your application's logic and content separate.

## Unit Testing & Code Coverage

`family-simulator` ships with unit tests using [PHPUnit](https://phpunit.de/getting-started-with-phpunit.html/).

- If PHPUnit is installed globally run `phpunit` to run the tests.

- If PHPUnit is not installed globally, install it locally throuh composer by running `composer install --dev`. Run the tests themselves by calling `vendor/bin/phpunit`.
