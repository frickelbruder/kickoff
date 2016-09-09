# KickOff ![As always: Travis is green](https://travis-ci.org/frickelbruder/kickoff.svg?branch=master)

##Installation

###Composer
```
php composer.phar require "frickelbruder/kickoff":"dev-master"
```

###Phar
For a simple quick you can download a precompiled phar-archive. 
Simply download the latest release at https://github.com/frickelbruder/kickoff/releases

##Simple usage
```
./bin/KickOff.php example/security.yml
```
For an easy start, just edit one of the provided files in example and change your host where appropriate.

## Integration into CD
```
./bin/KickOff.php -j build/logs/kickoff.xml example/security.yml
```
This generates a Junit compatible log file, which you can inject into your CD workflow as for example phpunit.

