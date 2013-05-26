Scraper
============

Performs multiple regular expression pattern matching on a data string. Allows 
you to easily name your captured offsets.

Usage
-----

```php
// Note: These are only examples, and not necessarily encouraged usage.
//
// Example 1: search IMDb for "test", and extract title, year, and code
$scraper = new Scraper(file_get_contents('http://www.imdb.com/find?q=test&s=tt'));
$scraper->matchAll();
$scraper->addPattern('/<a href="\/title\/([t0-9]*)\/[^>]*>([^<]*)<\/a> *\(([0-9]*)/', 'code,title,year');
foreach($scraper->process() as $result) {
    printf("%s (%s) - %s\n", $result['title'], $result['year'], $result['code']);
}

// Example 2: retrieve detailed information for "Citizen Kane"
$scraper = new Scraper(file_get_contents('http://www.imdb.com/title/tt0033467/'));
$scraper->setPatterns(array(
    'title'		=> '/itemprop="name">([^<|^\|]*)</',
    'runtime'	=> '/<time itemprop="duration" datetime="PT([0-9]*)M">/',
    'country'	=> '/a href="\/country\/.*" itemprop=\'url\'>([^<|^\|]*)</',
    'year'		=> '/a href="\/year\/([0-9]*)/',
    'director'	=> '/meta name="description" content="Directed by (.*)\.\s*With/',
    'cast'		=> '/meta name="description" content=".*With ([\w\s-,#&;\']+)/'
));

$results = (object)$scraper->process();

printf("%s was directed by %s and released in %s.\n", $results->title, $results->director, $results->year);
```