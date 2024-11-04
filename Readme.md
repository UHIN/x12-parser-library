# X12 Parser / Serializer

### Including this library

You'll need to include this library in your project in order to use it. First, add the following to the repositories section of your composer.json file:
```json
{
  ...
  "require": {
    ...
    "uhin/x12-parser": "^2.0.0"
    ...
  }
}
```

You can now include the library into your project by running:

```bash
composer require uhin/x12-parser
```

Once you're included the library in your project, you can now begin writing code to parse and serialize X12 EDI.


### Examples

##### Parsing a file

```php
use Uhin\X12Parser\Parser\X12Parser;

// Load the file into memory
$rawX12 = file_get_contents('path-to-your-file');

// Create a parser object
$parser = new X12Parser($rawX12);

// Parse the file into an object data structure
$x12 = $parser->parse();
// $x12 is now an X12 object containing all of the information from the X12 file
```


Note: If any segment/property can have multiples then it will always be parsed into an array, even if there is only one present. This is to avoid confusion on whether or not you are dealing with an array or an object.
```php
// Using the $x12 object from the examlpe above...

// Retrieving the GS06
$gs06 = $x12->ISA[0]->GS[0]->GS06;

// Setting the GS06 value
$x12->ISA[0]->GS[0]->GS06 = 'Changed Value';
```

##### Converting a parsed file back to X12

```php
use Uhin\X12Parser\Serializer\X12Serializer;

// Using the $x12 object from the examlpe above...
$serializer = new X12Serializer($x12);

// Options for serializing:
$serializer->addNewLineAfterSegment(true);
$serializer->addNewLineAfterIEA(true);

// Generate the raw X12 string
$rawX12 = $serializer->serialize();
```

### Unit Tests

First, make sure that you've placed your test files in the `tests/test-files` directory.

__NOTE:__ _Don't commit these files to git if they have any HIPPA data._

The current tests will look for the following files in the `tests/test-files` directory:
- `837.edi`
- `TA1.edi`

Once you have your files in place, you can run this command from the root of the library:

```bash
composer install
```

```bash
./vendor/bin/phpunit
./vendor/bin/phpunit --coverage-html tests/coverage
```
