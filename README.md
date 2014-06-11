  Zend Framework 2 DDD Persistence Module (Zend\Db)
===================================================
[![Build Status](https://secure.travis-ci.org/goten4/GtnPersistZendDb.png?branch=master)](http://travis-ci.org/goten4/GtnPersistZendDb)
[![Coverage Status](https://coveralls.io/repos/goten4/GtnPersistZendDb/badge.png?branch=master)](https://coveralls.io/r/goten4/GtnPersistZendDb)

## Introduction

**GtnPersistZendDb** is a Zend Framework 2 module providing the basics for persistence using Zend\Db
trying to follow [DDD principles](http://domaindrivendesign.org/books/#DDD).

## Requirements

* Zend Framework 2
* [GtnPersistBase](https://github.com/goten4/GtnPersistBase)

## Installation

Via composer or simply clone this project into your `./vendor/` directory and enable it in your
`./config/application.config.php` file.

Provided Classes and Interfaces
-------------------------------

* `GtnPersistZendDb\Infrastructure\ZendDbRepository` - Zend\Db Repository implementation.
* `GtnPersistZendDb\Service\ZendDbRepositoryFactory` - Factory for creating ZendDbRepository
* `GtnPersistZendDb\Service\ZendDbRepositoryAbstractFactory` - Abstract Factory for creating repositories

## See also

* [GtnPersistBase](https://github.com/goten4/GtnPersistBase)