Experimental SMW tarball downloading service

[![Build Status](https://travis-ci.org/JeroenDeDauw/TarballDownloader.svg)](https://travis-ci.org/JeroenDeDauw/TarballDownloader)

This is code for webpage on which users can request SMW and other extension tarballs to be build, which they can then download. This is useful for people that cannot run Composer on the server where their wiki is hosted.

## Requirements

* PHP 5.5 or later
* Linux (bash is used)
* `composer` command needs to exist
* `zip` command needs to exist

## Installation

Run `composer install`

## Testing

Run `phpunit`. Note that this will write things into the `/tmp` directory.

## Running the website

`www` should be web accessible. The entry point is `www/index.php`.

You can run the website during development by executing `php -S localhost:8000` in `www`.
