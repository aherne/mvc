# Abstract MVC API

`lucinda/abstract_mvc` is a small PHP 8.1+ library that defines the shared contracts and infrastructure for Lucinda MVC frameworks.

The current package is centered on:

- XML-driven application metadata
- route and resolver discovery
- constructor injection through facets
- event listener scheduling
- reusable response implementations for HTTP, console, redirects, and file downloads

The previous README described an older API shape. This version maps the documentation to the classes that currently exist in [`src`](./src).

## Table of Contents

- [What This Package Owns](#what-this-package-owns)
- [Suggested README Structure](#suggested-readme-structure)
- [Installation](#installation)
- [Core Flow](#core-flow)
- [XML Configuration](#xml-configuration)
- [Main Contracts](#main-contracts)
- [Responses](#responses)
- [Support Services](#support-services)
- [Exceptions](#exceptions)
- [Testing](#testing)

## What This Package Owns

This package is not a full front controller by itself. It provides the building blocks a higher-level MVC runtime can compose:

- [`Application`](./src/Application.php): loads XML configuration and exposes application, route, and resolver metadata
- [`RequestValidator`](./src/RequestValidator.php): reports the final route and response format selected by the host framework
- controller contracts:
  - [`Controller\ViewAware`](./src/Controller/ViewAware.php)
  - [`Controller\ViewUnaware`](./src/Controller/ViewUnaware.php)
- facet-based dependency injection:
  - [`Facet`](./src/Facet.php)
  - [`FacetRegistry`](./src/FacetRegistry.php)
  - [`ReflectionInjector`](./src/ReflectionInjector.php)
- event orchestration:
  - [`EventType`](./src/EventType.php)
  - [`EventScheduler`](./src/EventScheduler.php)
  - listener contracts under [`src/EventListener`](./src/EventListener)
- response contracts and implementations under [`src/Response`](./src/Response)

## Suggested README Structure

For this library, the most maintainable structure is:

1. Explain the package boundary first.
2. Show the runtime flow in 5-6 steps.
3. Document the XML schema with one valid example.
4. Group the API by responsibility instead of listing every class alphabetically.
5. Keep tests and implementation notes at the end.

That is the structure used below, because it matches how someone will actually integrate the package.

## Installation

```bash
composer require lucinda/abstract_mvc
```

Requirements:

- PHP `^8.1`
- `SimpleXML`

## Core Flow

A host MVC framework typically uses this package in the following order:

1. Extend [`Application`](./src/Application.php) and load the root XML file.
2. Build a [`RequestValidator`](./src/RequestValidator.php) that decides the final route and format.
3. Read route metadata from [`RouteInfo`](./src/XmlTags/RouteInfo.php) and resolver metadata from [`ResolverInfo`](./src/XmlTags/ResolverInfo.php).
4. Create controllers, listeners, and resolvers through [`ReflectionInjector`](./src/ReflectionInjector.php), using a [`FacetRegistry`](./src/FacetRegistry.php).
5. If a controller is `ViewAware`, pass its returned [`Response\View`](./src/Response/View.php) through [`Service\ViewDetector`](./src/Service/ViewDetector.php).
6. Resolve the detected view into a concrete [`Response`](./src/Response.php) and run it.

## XML Configuration

`Application` requires a root XML file whose top-level tags reference separate files. This is enforced by [`XmlReader`](./src/XmlReader.php).

### Root File

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xml>
<xml>
  <application ref="config/application"/>
  <resolvers ref="config/resolvers"/>
  <routes ref="config/routes"/>
</xml>
```

Each `ref` is resolved as `*.xml`.

### `application.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xml>
<xml>
  <application
    default_format="html"
    default_route="index"
    views_folder="app/views"
    views_extension="phtml"
    version="1.0.0"/>
</xml>
```

Supported attributes in [`ApplicationInfo`](./src/XmlTags/ApplicationInfo.php):

- `default_format`: required
- `default_route`: required
- `views_folder`: optional, but required if any route or controller uses template files
- `views_extension`: optional, but required if any route or controller uses template files
- `version`: optional

### `resolvers.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xml>
<xml>
  <resolvers>
    <resolver format="html" class="App\Response\HtmlResolver"/>
    <resolver format="json" class="App\Response\JsonResolver"/>
  </resolvers>
</xml>
```

Each resolver is validated by [`ResolverInfo`](./src/XmlTags/ResolverInfo.php):

- `format`: required
- `class`: required
- `class` must implement [`Response\Resolver`](./src/Response/Resolver.php)

### `routes.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xml>
<xml>
  <routes>
    <route id="index" controller="App\Controller\HomeController" view="home"/>
    <route id="api/users" controller="App\Controller\UsersController" format="json"/>
  </routes>
</xml>
```

Each route is validated by [`RouteInfo`](./src/XmlTags/RouteInfo.php):

- `id`: required
- `controller`: optional, but if present it must implement either [`Controller\ViewAware`](./src/Controller/ViewAware.php) or [`Controller\ViewUnaware`](./src/Controller/ViewUnaware.php)
- `view`: optional
- `format`: optional
- at least one of `controller` or `view` must be present

## Main Contracts

### Application and XML metadata

- [`Application`](./src/Application.php) loads configuration and exposes:
  - `getApplicationInfo(): ApplicationInfo`
  - `getResolvers(string $format): ?ResolverInfo`
  - `getRoutes(string $id): ?RouteInfo`
- [`XmlReader`](./src/XmlReader.php) and [`XmlReader\Element`](./src/XmlReader/Element.php) encapsulate the XML loading model.

### Controllers

- [`Controller\ViewAware`](./src/Controller/ViewAware.php): `run(): Response\View`
- [`Controller\ViewUnaware`](./src/Controller/ViewUnaware.php): `run(): void`

Use `ViewAware` when controller execution should produce view data. Use `ViewUnaware` when the controller only performs side effects.

### Request validation

- [`RequestValidator`](./src/RequestValidator.php) abstracts request parsing away from this library.
- It only needs to answer two questions:
  - `getRoute(): string`
  - `getFormat(): string`

### Facets and constructor injection

The package uses explicit constructor injection via facets:

- [`Facet`](./src/Facet.php): marker interface for injectable objects
- [`FacetRegistry`](./src/FacetRegistry.php): stores facets by class name or alias
- [`FacetCollection`](./src/FacetCollection.php): batch container returned by multi-faceted listeners
- [`ReflectionInjector`](./src/ReflectionInjector.php): creates objects and resolves constructor dependencies from the registry

Important constraints enforced by [`ReflectionInjector`](./src/ReflectionInjector.php):

- constructor parameters must be class or interface types
- builtin constructor types are rejected
- every requested facet must already exist in the registry

### Events and listeners

- [`EventType`](./src/EventType.php) defines five supported events:
  - `START`
  - `APPLICATION`
  - `REQUEST`
  - `RESPONSE`
  - `END`
- [`EventScheduler`](./src/EventScheduler.php) validates and stores listener classes per event.

Listener contracts:

- [`EventListener\UnFaceted`](./src/EventListener/UnFaceted.php): `run(): void`
- [`EventListener\Faceted`](./src/EventListener/Faceted.php): `run(): Facet`
- [`EventListener\MultiFaceted`](./src/EventListener/MultiFaceted.php): `run(): FacetCollection`

Rules enforced by [`EventScheduler`](./src/EventScheduler.php):

- listener classes must exist
- listener classes must implement [`EventListener`](./src/EventListener.php)
- `RESPONSE` listeners must also implement [`Response\Transformer\Transformer`](./src/Response/Transformer/Transformer.php)
- `RESPONSE` and `END` listeners must not be faceted
- duplicate registrations are rejected

## Responses

All response implementations implement [`Response`](./src/Response.php), which extends [`Runnable`](./src/Runnable.php).

### View and resolver contracts

- [`Response\View`](./src/Response/View.php): carries template path and structured data
- [`Response\Resolver`](./src/Response/Resolver.php): marker interface for response resolvers
- [`Response\ViewResolver`](./src/Response/ViewResolver.php): `resolve(View $view): string`

### Basic string responses

- [`Response\Basic`](./src/Response/Basic.php): abstract base for body-oriented responses
  - `setBody(string $body): void`
  - `resolve(View $view, ViewResolver $resolver): void`
  - `transformBody(Response\Transformer\Body $transformer): void`
- [`Response\Http`](./src/Response/Http.php): HTTP response with optional status and headers
- [`Response\Console`](./src/Response/Console.php): writes the body to `STDOUT`

### HTTP-oriented responses

- [`Response\HttpStatus`](./src/Response/HttpStatus.php): enum of supported HTTP status codes
- [`Response\Headers`](./src/Response/Headers.php): HTTP header collection
- [`Response\Blank`](./src/Response/Blank.php): empty HTTP responses for `204`, `205`, and `304`
- [`Response\ByStatus`](./src/Response/ByStatus.php): HTTP status with optional body
- [`Response\Redirect`](./src/Response/Redirect.php): redirects using `301`, `302`, `303`, `304`, `307`, or `308`

### File attachment responses

Under [`src/Response/Attachment`](./src/Response/Attachment):

- [`File`](./src/Response/Attachment/File.php): sends a whole file as an attachment
- [`Streamed`](./src/Response/Attachment/Streamed.php): streams a file in chunks
- [`Partial`](./src/Response/Attachment/Partial.php): handles ranged downloads with `206` or `416`
- [`FileToUpload`](./src/Response/Attachment/FileToUpload.php): file metadata and cleanup helper

### Response transformers

Under [`src/Response/Transformer`](./src/Response/Transformer):

- [`Transformer`](./src/Response/Transformer/Transformer.php): marker interface
- [`Body`](./src/Response/Transformer/Body.php): transforms a resolved body string
- [`Headers`](./src/Response/Transformer/Headers.php): contributes additional HTTP headers
- [`Status`](./src/Response/Transformer/Status.php): provides an HTTP status

## Support Services

The package also includes small orchestration services:

- [`Service\ResolverInfoDetector`](./src/Service/ResolverInfoDetector.php): picks the resolver matching the validated request format
- [`Service\ViewDetector`](./src/Service/ViewDetector.php): computes the final view file and view data using:
  - the application defaults
  - the validated route
  - the optional `View` returned by a controller

`ViewDetector` throws a configuration error if a view is needed but `views_folder` or `views_extension` is missing, or if the resolved template file does not exist.

## Exceptions

Main exception types exposed by the package:

- [`ConfigurationException`](./src/ConfigurationException.php): invalid class wiring or invalid runtime configuration
- [`FacetException`](./src/FacetException.php): invalid facet registration or injection
- [`TerminationException`](./src/TerminationException.php): termination marker exception
- [`XmlReader\Exception`](./src/XmlReader/Exception.php): invalid XML structure or missing XML files
- [`Response\Exception`](./src/Response/Exception.php): invalid response composition
- [`Response\Attachment\Exception`](./src/Response/Attachment/Exception.php): invalid attachment handling

## Testing

Tests live under [`tests`](./tests) and are executed via [`lucinda/unit-testing`](https://packagist.org/packages/lucinda/unit-testing).

Useful files:

- [`test.php`](./test.php): local test runner entry point
- [`unit-tests.xml`](./unit-tests.xml): unit test configuration
- [`TESTING_GUIDELINES.md`](./TESTING_GUIDELINES.md): project-specific testing notes

Run tests after installing dev dependencies:

```bash
composer install
php test.php
```
