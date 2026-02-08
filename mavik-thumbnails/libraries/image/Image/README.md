# Image
PHP Library for Image processing and creating thumbnails

Under construction.

## Tests
Some tests require GraphicsMagick to be installed on your system.

To run all tests including `Gmagick` and `Imagick` libraries, ensure that:
1. `gmagick` and `imagick` PHP extensions are installed/present on the system.
2. These extensions are **disabled** in your `php.ini` (not loaded by default). 
The test suite loads them dynamically using `dl()` and runs them in separate processes to avoid conflicts.

To run tests:
```bash
./vendor/bin/phpunit
```
