# witter
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fthe-real-sumsome%2Fwitter.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fthe-real-sumsome%2Fwitter?ref=badge_shield) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/the-real-sumsome/witter/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/the-real-sumsome/witter/?branch=main) [![Code Coverage](https://scrutinizer-ci.com/g/the-real-sumsome/witter/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/the-real-sumsome/witter/?branch=main) [![Build Status](https://scrutinizer-ci.com/g/the-real-sumsome/witter/badges/build.png?b=main)](https://scrutinizer-ci.com/g/the-real-sumsome/witter/build-status/main) [![Code Intelligence Status](https://scrutinizer-ci.com/g/the-real-sumsome/witter/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)

A 2009 recreation of old Twitter. Code needs refactoring

## How to Setup
## Requirements
Apache2/nginx

MySQL

PHP7.2+
## Steps
`git clone https://github.com/the-real-sumsome/witter.git`

Move git cloned files to `/var/www/html` or wherever your webroot is located

Edit `/static/config.inc.php`

Get a Recaptcha key at https://www.google.com/recaptcha/admin/create

Import the SQL file in the repository

This repository contains no copyrighted, or stolen code. All content here is 100% original. SpaceMy is not sponsored by, or hosted by MySpace.

## Features
- Selling Feature: Instant Messaging
- User security
    - We don't sell your data
    - Using BCRYPT to hash passwords
    - SQL injection safe
    - We don't log IPs
    - Top notch security
    - XSS injection safe (for the most part, if you find a vulnerability make a issue)
- Open source, public domain software
- Made by my 3 talented friends.

- More Specifics
    - Retweets
    - Likes
    - Replies

## How can I help?
If you know how to code, contribute using pull requests. If you like to test stuff, join the website and test the craziest stuff you can think of and try to find bugs, then report them in issues. Everything helps!

## Our Goal
Our goal is to bring back the days of Twitter (circa 2009)

If you would like to contribute, please fork this and make a pull request.

## License
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fthe-real-sumsome%2Fwitter.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fthe-real-sumsome%2Fwitter?ref=badge_large)
