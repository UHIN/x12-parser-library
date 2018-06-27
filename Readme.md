# X12 Parser / Serializer

### How to use

You'll need to include this library in your project in order to use it. You can do this with composer by...

### Unit Tests

First, make sure that you've placed your test files in the `tests/test-files` directory.

__NOTE:__ _Don't commit these files to git if they have any HIPPA data._ 

The current tests will look for the following files in the `tests/test-files` directory:
- `277.txt`
- `835.txt`
- `837.txt`
- `999.txt`
- `TA1.txt`

Once you have your files in place, you can run this command from the root of the library:

```bash
./vendor/bin/phpunit --bootstrap ./vendor/autoload.php --testdox tests
```