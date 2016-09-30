# KickOff [![Build Status](https://travis-ci.org/frickelbruder/kickoff.svg?branch=master)](https://travis-ci.org/frickelbruder/kickoff) [![Current packagist](https://img.shields.io/packagist/v/frickelbruder/kickoff.svg?style=flat)](https://packagist.org/packages/frickelbruder/kickoff)  
_A continous website monitoring tool._

## Why Kickoff?
You launch projects on a regular basis and have certain SEO, performance and security requirements which your websites should meet.
Those requirements could be:
- Providing an X-XSS-Protection header.
- Delivering an HTML document in less than 1 second.
- Redirects that should use a 301 header
- Cookies that should be accessible only via Http
- A title tag that should only be 70 characters long
- [...]

This tool aims to automate the process of checking all your requirements short before launch or after an deployment. This way you can simply click on deliver in your CI and sit back and watch your websites grow!

Kickoff can be integrated into your standard delivery process (integrated with jenkins, travis, etc.) to keep those requirements even after you launch. Kickoff makes sure your requirements won't get accidentally violated.

##Installation

###Composer
```
php composer.phar require frickelbruder/kickoff
```

###Phar
For a quick demo you can download a precompiled phar-archive. 
Simply download the latest release at https://github.com/frickelbruder/kickoff/releases

##Simple usage
```
./bin/kickoff.php run example/security.yml
```
For an easy start, just edit one of the provided files in example and change your host where appropriate.
This is what your tests should look like in the console:

![Example output on console of seo or security tests](https://frickelbruder.github.io/kickoff/images/example-output.png)

## Integration into CD
```
./bin/kickoff.php run -j build/logs/kickoff.xml example/security.yml
```
This generates a Junit compatible log file which you can inject into your CD workflow, for example as phpunit.
The command itself will return the number of errors as a result. Any errors should result in a failing build target.

## Issues and Bugs
If you find any issues I'd be happy if you filed an issue on the [issue board](https://github.com/frickelbruder/kickoff/issues/new).

## More Information
For more detailed information at [frickelbruder.github.io/kickoff](http://frickelbruder.github.io/kickoff/).
