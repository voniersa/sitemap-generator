# sitemap-generator
[![Unit tests](https://github.com/voniersa/sitemap-generator/actions/workflows/unit-tests.yaml/badge.svg?event=push)](https://github.com/voniersa/sitemap-generator/actions/workflows/unit-tests.yaml)

A small PHP script that searches your website for links and generates a sitemap.xml file out of them.

## Requirements
PHP Version 8.2 or higher

## How to use
You can install the library with composer:

```bash
composer require voniersa/sitemap-generator
```

## Execution
To execute the generator script simply execute the following command:
```bash
php vendor/voniersa/sitemap-generator/bin/sitemap-generator.php --base-url "URL_OF_YOUR_WEBSITE" --change-frequency "CHANGE_FREQUENCY_FOR_XML_SCHEMA" --ssl-verify "BOOLEAN" --output-dir "PATH_TO_YOUR_OUTPUT_DIRECTORY"
```

You need to replace the __PLACEHOLDER_STRINGS__ in the command with your own values.

### Explanation of the script arguments
| argument name | description | default value
| --- | --- | --- |
| --base-url | The URL of your website from which a sitemap is to be created | https://localhost |
| --change-frequency | The frequency with which the page is expected to change. This value provides general information to search engines. For more information. For more information and all valid values, see the [documentation on sitemaps](https://www.sitemaps.org/de/protocol.html). | monthly |
| --ssl-verify | Acknowledge SSL certificate errors. | false |
| --output-dir | Path to the directory, where the generated sitemap.xml file is saved | ./ |


## License
This library is released under the MIT Licence. See the bundled [LICENSE file](LICENSE) for details.

## Author
Sascha Vonier ([@voniersa](https://github.com/voniersa))
