# KickOff [![Build Status](https://travis-ci.org/frickelbruder/kickoff.svg?branch=master)](https://travis-ci.org/frickelbruder/kickoff) [![Current packagist](https://img.shields.io/packagist/v/frickelbruder/kickoff.svg?style=flat)](https://packagist.org/packages/frickelbruder/kickoff)

## Why Kickoff
You launch projects on a regular basis and have certain SEO, performance and security requirements which your websites should meet.
Those could be:
- Provide an X-XSS-Protection header.
- Deliver an HTML document in less than 1 second.
- Your redirects should use a 301 header
- Your cookie should be accessible via HttpOnly
- Your title tag should only be 70 characters long
- [...]

This tool aims to automate the process of checking all your requirements short before launch or after an deployment, so you can just click on deliver in your CI and sit back and see your websites become popular.
Kickoff can be integrated into your standard delivery process (such as with jenkins, travis or any other) to keep those requirements even after the launch day, so that your requirements won't get accidentally dissatisfied.

##Installation

###Composer
```
php composer.phar require frickelbruder/kickoff
```

###Phar
For a quick check you can download a precompiled phar-archive. 
Simply download the latest release at https://github.com/frickelbruder/kickoff/releases

##Simple usage
```
./bin/kickoff.php run example/security.yml
```
For an easy start, just edit one of the provided files in example and change your host where appropriate.
This is, how your tests could look like on the console:

![Example output on console of seo or security tests](https://frickelbruder.github.io/kickoff/images/example-output.png)

## Integration into CD
```
./bin/kickoff.php run -j build/logs/kickoff.xml example/security.yml
```
This generates a Junit compatible log file, which you can inject into your CD workflow as for example phpunit.
The command itself will return the number of errors as result. So any errors should result in a failing build target.

## Issues and Bugs
If you find any issues, I'd be happy, if you file an issue on the [issue board](https://github.com/frickelbruder/kickoff/issues/new).

## More Infos
Get detailed information at [frickelbruder.github.io/kickoff](http://frickelbruder.github.io/kickoff/).