[![Build Status](https://travis-ci.org/suitmedia/frontend-lighthouse-audit.svg?branch=master)](https://travis-ci.org/suitmedia/frontend-lighthouse-audit) 
[![codecov](https://codecov.io/gh/suitmedia/frontend-lighthouse-audit/branch/master/graph/badge.svg)](https://codecov.io/gh/suitmedia/frontend-lighthouse-audit) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/suitmedia/frontend-lighthouse-audit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/suitmedia/frontend-lighthouse-audit/?branch=master) 
[![StyleCI](https://github.styleci.io/repos/170811415/shield?branch=master)](https://github.styleci.io/repos/170811415) 
[![Total Downloads](https://poser.pugx.org/suitmedia/frontend-lighthouse-audit/d/total.svg)](https://packagist.org/packages/suitmedia/frontend-lighthouse-audit) 
[![Latest Stable Version](https://poser.pugx.org/suitmedia/frontend-lighthouse-audit/v/stable.svg)](https://packagist.org/packages/suitmedia/frontend-lighthouse-audit) 
[![License: MIT](https://poser.pugx.org/laravel/framework/license.svg)](https://opensource.org/licenses/MIT) 

# Frontend Lighthouse Audit

> A simple tool to audit all of the frontend HTML templates using `lighthouse-ci` CLI command.

<p align="center">
    <br>
    <img src="https://raw.githubusercontent.com/suitmedia/frontend-lighthouse-audit/master/docs/example.png" alt="Lighthouse Audit Example" height="200">
    <br><br>
</p>

------

## Synopsis

This package will help you to measure and audit the quality of your frontend HTML templates. It will start a web-server automatically on the given document root path, scan all HTML files and analyse them one by one. You can also run this tool in your CI pipeline.

## Table of contents

* [Compatibility](#compatibility)
* [Requirements](#requirements)
* [Setup](#setup)
* [CLI Usage](#cli-usage)
* [License](#license)

## Compatibility

This package only supports PHP version `7.1` or higher.

## Requirements

This package is dependent to the [lighthouse-ci](https://github.com/andreasonny83/lighthouse-ci) package and `Chrome` /`Chromium` web browser, so you need to install them first. 

You can install `lighthouse-ci` easily, using this command:

```bash
$ npm install -g lighthouse-ci
```

## Setup

Install the package globally using Composer :

```bash
$ composer global require suitmedia/frontend-lighthouse-audit
```

## CLI Usage

```bash
$ lighthouse-audit -h

Usage:
  lighthouse-audit [options] [--] <path>

Arguments:
  path                                   Specify the path of a directory to analyse.

Options:
  -S, --server[=SERVER]                  Define the address and port that PHP web-server should serve. <address>:<port> [default: "localhost:8000"]
      --mode[=MODE]                      Define the mode to run Lighthouse audit. Option: mobile,desktop [default: "mobile"]
      --performance[=PERFORMANCE]        Define the minimal performance score for audit to pass [default: "80"]
      --best-practices[=BEST-PRACTICES]  Define the minimal best-practices score for audit to pass [default: "80"]
      --accessibility[=ACCESSIBILITY]    Define the minimal accessibility score for audit to pass [default: "80"]
      --seo[=SEO]                        Define the minimal seo score for audit to pass [default: "80"]
      --pwa[=PWA]                        Define the minimal pwa score for audit to pass [default: "0"]
      --except[=EXCEPT]                  Provide a list of filenames that you wish to exclude, separated by commas.

      --chrome-flags[=CHROME-FLAGS]      Custom flags to pass to Chrome (space-delimited). For a full list of flags,
                                         see http://peter.sh/experiments/chromium-command-line-switches/.

  -h, --help                             Display this help message
  -V, --version                          Display this application version
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.