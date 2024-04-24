<p align="center"><a href="http://poxsoft.com" target="_blank"><img src="src/img/poxsoft.png" width="800" alt="Poxsoft Logo"></a></p>

<p align="center">
    <a href="https://github.com/Poxsoft/lucendi"><img src="https://img.shields.io/badge/Github-Free_code-red?logo=github&logoColor=white" alt="Build Status"></a>
    <a href="https://packagist.org/packages/lucendi/laravel"><img src="https://img.shields.io/badge/downloads-1M-green" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/lucendi/laravel"><img src="https://img.shields.io/badge/packagist-v1.0.0-blue" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/lucendi/laravel"><img src="https://img.shields.io/badge/license-GPL_2.0-green" alt="License"></a>
</p>

Welcome to the **lucendi/laravel**.  package. This package is designed to offer advanced features and facilitate integration into Laravel projects.

[Installation](#1)
[Installation Manual for Laravel](#2)
[Installation Manual for Projects Unrelated to Laravel](#3)

## Feature
- Feature 1: Easy and quick configuration.
- Feature 2: Repository of plugins specifically designed and developed for Laravel starting from version 10 onwards.
- Feature 3: Facilitates the development of custom projects by using plugins with specific functions.
- Feature 4: Allows the Laravel community to create and distribute their own plugins, both free and paid.
- Feature 5: Opens new opportunities for companies and freelancers who develop software using the Laravel framework.
- Feature 6: Developed in PHP, enabling its use on platforms other than Laravel.

## Installation {#1}
To install the package, you can use Composer with the following command:

```bash
composer require lucendi/laravel
```

Sign up for free at [Larapox](http://larapox.com/login),  to get your user and access token.

## For Laravel projects {#2}
Declare the following environment variables in your Laravel .env file.

```bash
LARAPOX_APP_KEY=
LARAPOX_APP_SECRET=
LARAPOX_APP_USERNAME=
```

## For projects unrelated to Laravel (PHP). {#3}
Go to 'src/Lucendi.php' downloaded by Composer and replace the constructor with the following code:

```bash
public function __construct()
{
    $this->apiURL = 'https://www.larapox.com/';
    $this->apiKey = "LARAPOX_APP_KEY";
    $this->apiSecret = "LARAPOX_APP_SECRET";
    $this->apiUser = "LARAPOX_APP_USERNAME";
    if (empty($this->apiKey) || empty($this->apiSecret) || empty($this->apiUser)) {
        throw new \Exception("Las claves de la API no están configuradas correctamente.");
    }
}
```

Developed by [Poxsoft](http://poxsoft.com)