# KickOff [![Build Status](https://travis-ci.org/frickelbruder/kickoff.svg?branch=master)](https://travis-ci.org/frickelbruder/kickoff) [![Current packagist](https://img.shields.io/packagist/v/frickelbruder/kickoff.svg?style=flat)](https://packagist.org/packages/frickelbruder/kickoff)
_A continuous website monitoring tool._

## Why Use KickOff?
While each project you launch may have a different feature set, they often share many of the same performance, SEO and security requirements. Let's look at an example set:

- Provide an X-XSS-Protection header on all POST requests.
- Deliver all HTML documents in less than 1 second.
- All redirects should contain a 301 header.
- Cookies should be accessible via HttpOnly.
- Your title tag should be no longer than 70 characters long.
- [...]

This tool aims to automate the process of checking your list of requirements shortly before launch or directly after a deployment. Deploy, sit back and leave the rest of the work to KickOff.

KickOff can be integrated into your standard delivery process (such as with Jenkins, Travis CI, etc.) to help maintain your list of requirements after each deployment, not just on launch day. That is why we call KickOff a truly continuous website monitoring tool.

##Installation

###Composer
```
php composer.phar require frickelbruder/kickoff
```

###Phar
If you would prefer a precompiled phar-archive, simply download the latest release at https://github.com/frickelbruder/kickoff/releases

##Simple Example
To get started, edit one of the provided files in the `example` directory and change your host where appropriate. Now run the test:
```
./bin/kickoff.php run example/security.yml
```
Once your test is complete you will see the results:

![Example console outputfor SEO or security tests](https://frickelbruder.github.io/kickoff/images/example-output.png)

## Integration into CD
```
./bin/kickoff.php run -j build/logs/kickoff.xml example/security.yml
```
This generates a JUnit compatible log file, which you can inject into your CD work flow (with PHPUnit for example).

The command itself will return the number of errors as a result, so any errors should result in a failing build target.

## Issues and Bugs
Bug reports are gladly accepted in the GitHub [issue tracker](https://github.com/frickelbruder/kickoff/issues/new).

## More Info
More detailed information can be found at [frickelbruder.github.io/kickoff](http://frickelbruder.github.io/kickoff/).