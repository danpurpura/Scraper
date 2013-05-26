Scaper
============

Performs multiple regular expression pattern matching on a data string. Allows 
you to easily name your captured offsets.

Usage
-----

```php
// search IMDb for "test"
// this is only an example, and not actually encouraged usage
$scraper = new Scraper(file_get_contents('http://www.imdb.com/find?q=test&s=tt'));
$scraper->matchAll();
$scraper->addPattern('/<a href="\/title\/([t0-9]*)\/[^>]*>([^<]*)<\/a> *\(([0-9]*)/', 'code,title,year');
foreach($scraper->process() as $result) {
    printf("%s (%s) - %s\n", $result['title'], $result['year'], $result['code']);
}

$store->add('key', 'value'); // or $store['key'] = 'value';
echo $store->has('key')."\n";
echo $store->get('key')."\n"; // or echo $store['key'];
echo $store; // automatically serializes to: q1bKTq1UslIqS8wpTVWqBQA=
```

Example
-------

See example.php for an example on how to use the store on a web app for storing
a user's settings.