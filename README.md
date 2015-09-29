Experimental SMW tarball downloading service

Caution: making this service publicly accessible is not safe and might result in
arbitrary remote code execution.

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