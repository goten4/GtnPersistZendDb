  Zend Framework 2 DDD Persistence Module (Zend\Db)
===================================================

## Introduction

**ZfPersistenceZendDb** is a Zend Framework 2 module providing the basics for persistence using Zend\Db
trying to follow [DDD principles](http://domaindrivendesign.org/books/#DDD).

## Requirements

* Zend Framework 2
* [ZfPersistenceBase](https://github.com/goten4/ZfPersistenceBase)

## Installation

Via composer or simply clone this project into your `./vendor/` directory and enable it in your
`./config/application.config.php` file.

Provided Classes and Interfaces
-------------------------------

* `ZfPersistenceBase\Infrastructure\ZendDbRepository` - Zend\Db Repository implementation.
* `ZfPersistenceBase\Infrastructure\ZendDbRepositoryFactory` - Factory for creating ZendDbRepository

## See also

* [ZfPersistenceBase](https://github.com/goten4/ZfPersistenceBase)
* [ZfPersistenceDoctrineORM](https://github.com/goten4/ZfPersistenceDoctrineORM)
