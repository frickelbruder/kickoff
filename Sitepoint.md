#Keep your websites requirements with kickoff
When you are developing websites, you normally have a multitude of requirements which more describe the environmental aspects than the website itself. Those requirements are often so implicit, that you are not even aware of them.  
But it is vital to keep an eye on aspects of your page like SEO, performance and security, to keep your website in the search engines and your users safe.  
Some of those aspects could be 
- Provide an X-XSS-Protection header.
- Deliver an HTML document in less than 1 second.
- Your redirects should use a 301 header
- Your cookie should be accessible via HttpOnly
- Your title tag should only be 70 characters long
- [...]

Kickoff helps you to define those aspects and test them once or in your continous delivery life cycle.

##Installation
The easiest way to install kickoff is via composer:
```
php composer.phar require frickelbruder/kickoff:dev-master
```
This adds kickoff.php to your composers bin path.

##Configuration
###Defaults
You should start by putting a kickoff.yml file in your project root. A simple start would look like this:
```
defaults:
    target:
        scheme: http://
        host: www.somehost.com
```
Of course you replace "www.somehost.com" with your projects website url.  
This defines the defaults for your website. You can override them later, to add certain tests for only certain paths.

###Sections
Now it's time to add some rules to test your website:
```
Sections:

    website:
        rules:
            - HttpHeaderXSSProtectionSecure
            - HttpHeaderExposeLanguage
            - HttpRequestTime:
                - ["maxTransferTime", 500]
```
We added a section named website, to which we added a bunch of rules. These rules check:
- Existence of an X-XSS-Protection header
- Non-existence of X-Powered-By header
- Delivering the website in 500ms

You might notice the ["maxTransferTime", 500] instruction. The default of the "HttpRequestTime" rule is to check, whether the website was delivered in 1 second, which might be sufficient for many sites.  
But from a usability and SEO point of view it might be better to ensure a faster delivery of the page.  
Some rules allow the configuration of its properties. As such you can add key-value-pairs to override the existing configuration.

###Running your tests
Our kickoff.yml should already work. So lets try it:
```
./vendor/bin/kickoff.php run kickoff.yml
```
Your console outputs some dots on successfully tested rules or red "F"s on failed rules. The layout is similar to phpunit, so you should get easily familiar to it.  
If some of your tests fail you get additional information below the dottes and Fs. Your normally get the exact error message, why a rule failed, as well as the section and the exact rule which failed.   
This should give you a good hint, where your requirements are actually failing.
 
###Configuring more Sections
The Sections-area can hold as many sections as you require. So lets add another section to out config file. 

```
Sections:
    [...]
    
    styles:
        config:
            uri: css/styles.css
        rules:
            - HttpHeaderHasFarFutureExpiresHeader
            - HttpHeaderResourceIsGzipped
```
We defined a new section named "styles". We use this, to test specific rules for our stylesheets as delivering css files gzipped or with a far future expires header to leverage browser caching.  
But besides the rules area, this section has a config area. In this config area we can override the default target values such as host, scheme or (in out example empty) uri.
This allows us, to define a path to a css file, so we can fetch the file and inspect it to match all our rules.

You are obviously able also define a similar additional "javascripts" section.
```
Sections:
    [...]
    
    javascripts:
        config:
            uri: js/main.js
        rules:
            - HttpHeaderHasFarFutureExpiresHeader
            - HttpHeaderResourceIsGzipped
```

On the other hand, you are now able to check the existence of some files, which should always be there to ensure your projects quality. 
```
Sections:
    [...]
    
    favicon.ico:
        config:
            uri: favicon.ico
        rules:
            - HttpHeaderResourceFound
    robots.txt:
        config:
            uri: robots.txt
        rules:
            - HttpHeaderResourceFound
```  

###Best practices
Since a normal webpage consists of many different resource types (like html-pages, css, js, images, maybe videos), you should test them all with their individual rule set. So your kickoff.yml file should consist at least of the following sections:
- website
- stylesheets
- javascripts
- images
If your requirements also include to have an existing favicon.ico file in the root dir or a robot.txt file, you could include them as well:
- robots.txt
- favicon.ico
To satisfy some SEO needs you should test, that non existing page are returning a proper 404 HTTP status header. You should also test your redirects to use a 301 header

###Integrating kickoff into your CI
This by itself is all nice, but off course you don't want to run this by hand after you deployed your code. So you need to integrate kickoff into your CD/CI workflow. This is easy enough, since the kickoff command returns the amount of errors. So just add a target to your build.xml which fails on error.  
Additionally kickoff allows you to export a junit compatible log:
```
./vendor/bin/kickoff.php run -j build/logs/kickoff.xml kickoff.yml
```
This log can be read by the junit parser, which is provided by many continous integration automation servers. It allows to show the failed rules in your common CI environment.

###Conclusion
Kickoff is a nice tool to keep the environmental aspects of your website through the whole live of your website. By defining rules for your website and integrating kickoff into your deployment workflow you can be sure to notice early, when all of a sudden some of your requirements fail.
 
###Even more
We covered just a few rules in the examples above, but there is a growing number of rules. They are all documented on the project page (and of course in the code). So be sure to visit the documentation.  
Documentation: http://frickelbruder.github.io/kickoff/  
Source: https://github.com/frickelbruder/kickoff
