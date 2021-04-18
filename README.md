# Abstract MVC API

Table of contents:

- [About](#about)
- [Configuration](#configuration)
- [Unit Tests](#unit-tests)
- [Reference Guide](#reference-guide)
- [Specifications](#specifications)
    - [How Is Response Format Detected](#how-is-response-format-detected)
    - [How Are View Resolvers Located](#how-are-view-resolvers-located)
    - [How Is Route Detected](#how-is-route-detected)
    - [How Are Controllers Located](#how-are-controllers-located)

## About

This API was created to contain parts of a MVC API that do not relate to STDIN type (console, url request or exception to be handled). It serves as a foundation for:

- [STDOUT MVC API](https://github.com/aherne/php-servlets-api): where STDIN comes from URL requests
- [STDERR MVC API](https://github.com/aherne/errors-api): where STDIN comes from STDERR of url/console requests
- Console MVC API (to be done): where STDIN comes from console requests

API is fully PSR-4 compliant, only requiring PHP7.1+ interpreter and SimpleXML extension. To quickly see how it works, check:

- **[configuration](#configuration)**: setting up an XML file where this API is configured
- **[reference guide](#reference-guide)**: describes all API classes, methods and fields relevant to developers
- **[unit tests](#unit-tests)**: API has 100% Unit Test coverage, using [UnitTest API](https://github.com/aherne/unit-testing) instead of PHPUnit for greater flexibility

## Configuration

To configure this API you must have a XML with following tags inside:

- **[application](#application)**: (mandatory) configures your application on a general basis
- **[resolvers](#resolvers)**: (mandatory) configures formats in which your application is able to resolve responses to
- **[routes](#routes)**: (mandatory) configures routes that bind requested resources to controllers and views

### Application

Maximal syntax of this tag is:

```xml
<application default_format="..." default_route="..." version="...">
	<paths controllers="..." resolvers="..." validators="..." views="..."/>
</application>
```

Where:

- **application**: (mandatory) holds settings to configure your application based on attributes and tag:
    - *default_format*: (mandatory) defines default display format (extension) for your application.<br/> Must match a *format* attribute in **[resolvers](#resolvers)**!
    - *default_route*: (mandatory) defines implicit route when your application is invoked with none.<br/> Must match a *id* attribute in **[routes](#routes)**!
    - *version*: (optional) defines your application version, to be used in versioning static resources.
    - **paths**: (optional) holds where core components used by API are located based on attributes:
        - *controllers*: (optional) holds folder in which user-defined controllers will be located  
        - *resolvers*: (mandatory) holds folder in which user-defined view resolvers will be located
        - *views*: (optional) holds folder in which user-defined views will be located (if HTML)

Tag example:

```xml
<application default_format="html" default_route="" version="1.0.1">
	<paths controllers="application/controllers" resolvers="application/resolvers" views="application/views"/>
</application>
```

### Resolvers

Base syntax of this tag is:

```xml
<resolvers>
	<resolver format="..." content_type="..." class="..."/>
	...
</resolvers>
```
Where:

- **resolvers**: (mandatory) holds settings to resolve views based on response format (extension). Holds a child for each format supported:
    - **resolver**: (mandatory) configures a format-specific view resolver based on attributes:
        - *format*: (mandatory) defines display format (extension) handled by view resolver.<br/>Example: "html"
        - *content_type*: (mandatory) defines content type matching display format above. <br/>Example: "text/html"
        - *class*: (mandatory) name of user-defined class that will resolve views (including namespace or subfolder), found in folder defined by *resolvers* attribute @ **[application](#application)**.<br/>Must be a [Lucinda\MVC\ViewResolver](#abstract-class-viewresolver) instance!

Tag example:

```xml
<resolvers>
    <resolver format="html" content_type="text/html" class="ViewLanguageRenderer" charset="UTF-8"/>
    <resolver format="json" content_type="application/json" class="JsonRenderer" charset="UTF-8"/>
</resolvers>
```

### Routes

Maximal syntax of this tag is:

```xml
<routes>
    <route id="..." controller="..." view="..." format="..."/>
    ...
</routes>
```

Where:

- **routes**: (mandatory) holds routing rules for handled requests
    - **route**: (optional) holds routing rules specific to a requested URI based on attributes:
        - *id*: (mandatory) unique route identifier (eg: requested requested resource url without trailing slash)<br/>Example: "users/(name)"
        - *controller*: (optional) holds user-defined controller (including namespace or subfolder) that will mitigate requests and responses based on models, found in folder defined by *controllers* attribute @ **[application](#application)**.<br/>Must be a [Lucinda\MVC\Runnable](#interface-runnable) instance!
        - *view*: (optional) holds user-defined template file that holds the recipe of response for request. Example: "homepage"
        - *format*: (optional) holds response format, if different from *default_format* @ [application](#application).<br/>Must match a *format* attribute @ **[resolvers](#resolvers)**!

Tag example:

```xml
<routes>
    <route id="index" controller="HomepageController" view="index"/>
    <route id="user/(id)" controller="UserInfoController" view="user-info" format="json"/>
</routes>
```

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [test.php](https://github.com/aherne/mvc/blob/master/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/mvc/blob/master/unit-tests.xml): sets up unit tests and mocks "loggers" tag
- [tests](https://github.com/aherne/mvc/blob/master/tests): unit tests for classes from [src](https://github.com/aherne/mvc/blob/master/src) folder

## Reference Guide

These classes are fully implemented by API:

- [Lucinda\MVC\Application](#class-application): reads [configuration](#configuration) XML file and encapsulates information inside
    - [Lucinda\MVC\Application\Route](#class-application-route): encapsulates [route](#routes) XML tag matching *id* of request handled
    - [Lucinda\MVC\Application\Format](#class-application-format): encapsulates [resolver](#resolvers) XML tag matching response *format* for request handled
- [Lucinda\MVC\Response](#class-response): encapsulates response to send back to caller
    - [Lucinda\MVC\Response\Status](#class-response-status): encapsulates response HTTP status
    - [Lucinda\MVC\Response\View](#class-response-view): encapsulates view template and data that will be bound into a response body

Following abstract classes require to be extended by developers in order to gain an ability:

- [Lucinda\MVC\Runnable](#interface-runnable): defines blueprint for a component whose logic can be *run*
- [Lucinda\MVC\ViewResolver](#abstract-class-viewresolver): encapsulates conversion of [Lucinda\MVC\Response\View](#class-response-view) into a [Lucinda\MVC\Response](#class-response) body

### Class Application

Class [Lucinda\MVC\Application](https://github.com/aherne/mvc/blob/master/src/Application.php) encapsulates information detected from XML and defines following public methods relevant to developers:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| getVersion | void | string | Gets application version based on *version* attribute @ [application](#application) XML tag |
| getTag | string $name | [\SimpleXMLElement](https://www.php.net/manual/en/class.simplexmlelement.php) | Gets a pointer to a custom tag in XML root |

Other public methods are relevant only to APIs built on top of this.

### Class Application Route

Class [Lucinda\MVC\Application\Route](https://github.com/aherne/mvc/blob/master/src/Application/Route.php) encapsulates information detected from matching [route](#routes) XML tag and defines following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| getID | void | string | Gets route unique identifier based on value of *id* XML attribute |
| getController | void | string | Gets controller class name/path/namespace based on value of *controller* XML attribute |
| getFormat | void | string | Gets custom route-specific display format based on value of *format* XML attribute matching a *format* attribute of [resolver](#resolvers) XML tag |
| getView | void | string | Gets view path based on value of *view* XML attribute |

### Class Application Format

Class [Lucinda\MVC\Application\Format](https://github.com/aherne/mvc/blob/master/src/Application/Format.php) encapsulates information detected from matching [resolver](#resolvers) XML tag and defines following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| getName | void | string | Gets reponse format (extension) based on value of *format* XML attribute |
| getCharacterEncoding | void | string | Gets response character encoding based on value of *charset* XML attribute |
| getContentType | void | string | Gets response content type based on value of *content_type* XML attribute |
| getViewResolver | void | string | Gets view resolver class based on value of *class* attribute |

To better understand how views will be resolved in the end, check [How Are Views Resolved](#how-are-views-resolved) documentation below!

### Class Response

Class [Lucinda\MVC\Response](https://github.com/aherne/mvc/blob/master/src/Response.php) encapsulates operations to be used in generating response. It defines following public methods relevant to developers:


| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| getBody | void | string | Gets response body saved by method below. |
| setBody | string $body | void | Sets response body. |
| getStatus | void | [Lucinda\MVC\Response\Status](#class-response-status) | Gets response http status based on code saved by method below. |
| setStatus | int $code | void | Sets response http status code. |
| headers | void | array | Gets all response http headers saved by methods below. |
| headers | string $name | ?string | Gets value of a response http header based on its name. If not found, null is returned! |
| headers | string $name, string $value | void | Sets value of response http header based on its name. |
| redirect | string $location, bool $permanent=true, bool $preventCaching=false | void | Redirects caller to location url using 301 http status if permanent, otherwise 302. |
| view | void | [Lucinda\MVC\Response\View](#class-response-view) | Gets a pointer to view encapsulating data based on which response body will be compiled |

When API completes handling, it will call *commit* method to send headers and response body back to caller!

### Class Response Status

Class [Lucinda\MVC\Response\Status](https://github.com/aherne/mvc/blob/master/src/Response/Status.php) encapsulates response HTTP status and defines following public methods relevant to developers:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| getDescription | void | string | Gets response http status code description (eg: "not modified"). |
| getId | void | int | Sets response http status code. |

### Class Response View

Class [Lucinda\MVC\Response\View](https://github.com/aherne/mvc/blob/master/src/Response/View.php) implements [\ArrayAccess](https://www.php.net/manual/en/class.arrayaccess.php) and encapsulates template and data that will later be bound to a response body. It defines following public methods relevant to developers:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| getFile | void | string | Gets location of template file saved by method below. |
| setFile | string | int | Sets location of template file to be used in generating response body. |
| getData | void | array | Gets all data that will be bound to template when response body will be generated. |

By virtue of implementing [\ArrayAccess](https://www.php.net/manual/en/class.arrayaccess.php), developers are able to work with this object as if it were an array:

```php
$this->response->view()["hello"] = "world";
```

### Interface Runnable

Interface [Lucinda\MVC\Runnable](https://github.com/aherne/mvc/blob/master/src/Runnable.php) interface it implements, class comes with following public method:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| run |  | void | Executes component logic |

### Abstract Class ViewResolver

Abstract class [Lucinda\MVC\ViewResolver](https://github.com/aherne/mvc/blob/master/src/ViewResolver.php) implements [Lucinda\MVC\Runnable](https://github.com/aherne/mvc/blob/master/src/Runnable.php) and encapsulates conversion of [Lucinda\MVC\Response\View](#class-response-view) to response body for final response format.

Developers need to implement *run* method for each resolver, where they are able to access following protected fields injected by API via constructor:

| Field | Type | Description |
| --- | --- | --- |
| $application | [Lucinda\MVC\Application](#class-application) | Gets application information detected from XML. |
| $response | [Lucinda\MVC\Response](#class-response) | Gets access to object based on which response can be manipulated. |

Example of a resolver for *html* format:

```php
class HtmlResolver extends Lucinda\MVC\ViewResolver
{
    public function run(): void
    {
        $view = $this->response->view();
        if ($view->getFile()) {
            if (!file_exists($view->getFile().".html")) {
                throw new Exception("View file not found");
            }
            ob_start();
            $_VIEW = $view->getData();
            require($view->getFile().".html");
            $output = ob_get_contents();
            ob_end_clean();
            $this->response->setBody($output);
        }
    }
}
```

Defined in XML as:

```xml
<resolver format="html" content_type="text/html" class="HtmlResolver" charset="UTF-8"/>
```

In order to better understand how view resolvers work, check [How Are View Resolvers Located](#how-are-view-resolvers-found) section below!

## Specifications

Since this API is a skeleton to build MVC APIs on top it comes with a series of specifications that may or may not be automatically implemented here:

- [How Is Response Format Detected](#how-is-response-format-detected)
- [How Are View Resolvers Located](#how-are-view-resolvers-located)
- [How Is Route Detected](#how-is-route-detected)
- [How Are Controllers Located](#how-are-controllers-located)

### How Is Response Format Detected

To better understand how *default_format* and *default_route* attributes in **[application](#application)** XML tag play together with *format* attribute in **[routes](#routes)** tag and *format* attribute in **[resolvers](#resolvers)** tag, let's take this XML for example:

```xml
<application default_route="index" default_format="html" ...>
	...
</application>
<routes>
    <route id="index" .../>
    <route id="blog" format="json" .../>
    <route id="users" .../>
    ...
</routes>
<resolvers>
    <resolver format="html" .../>
    <resolver format="json" .../>
    ...
</resolvers>
```

There will be following situations for above:

| If Route Detected | Then Format Detected | Description |
| --- | --- | --- |
| index | html | Because no specific *format* is set, value of *default_format* is used |
| users | html | Because no specific *format* is set, value of *default_format* is used |
| blog | json | Because route detected has *format* its value is used |

This logic requires to be implemented by child APIs because the nature of resolvers differs based on STDIN type!

### How Are Views Resolvers Located

To better understand how *resolvers* and *default_format* attributes in **[application](#application)** XML tag play together with *format* and *class* attributes in **[resolvers](#resolvers)** tag, let's take this XML for example:

```xml
<application default_format="html" ...>
	<paths resolvers="FOLDER" .../>
</application>
...
<resolvers>
    <resolver format="html" class="CLASS" .../>
</resolvers>
```

There will be following situations for above:

| FOLDER | CLASS | File Loaded | Class Instanced |
| --- | --- | --- | --- |
| application/resolvers | HtmlResolver | application/resolvers/HtmlResolver.php | HtmlResolver |
| application/resolvers | foo/HtmlResolver | application/resolvers/foo/HtmlResolver.php | HtmlResolver |
| application/resolvers | \Foo\HtmlResolver | application/resolvers/HtmlResolver.php | \Foo\HtmlResolver |
| application/resolvers | foo/\Bar\HtmlResolver | application/resolvers/foo/HtmlResolver.php | \Bar\HtmlResolver |

This logic is entirely implemented by this API! Developers only need to plug in suitable [Lucinda\MVC\ViewResolver](#Abstract Class ViewResolver) classes in XML.

### How Is Route Detected

To better understand how *default_route* attribute in **[application](#application)** XML tag plays together with *id* attribute in **[routes](#routes)** tag, let's take this XML for example:

```xml
<application default_route="index" ...>
	...
</application>
<routes>
    <route id="index" .../>
    <route id="blog" .../>
    ...
</routes>
```

Assuming STDIN comes from HTTP requests, there will be following situations for above:

| If Request Is | Then Route ID Detected | Description |
| --- | --- | --- |
| / | index | Because no specific page was requested, that identified by *default_route* is used |
| /blog | blog | Because page is routed, that whose id matches request is used |

This logic requires to be implemented by child APIs since logic of request depends on STDIN type!

### How Are Controllers Located

To better understand how *controllers* and *default_format* attributes in **[application](#application)** XML tag play together with *id* and *controller* attributes in **[routes](#routes)** tag, let's take this XML for example:

```xml
<application default_route="default" ...>
	<paths controllers="FOLDER" .../>
</application>
...
<routes>
    <route id="default" controller="CLASS" .../>
</routes>
```

There will be following situations for above:

| FOLDER | CLASS | File Loaded | Class Instanced |
| --- | --- | --- | --- |
| application/controllers | UserController | application/controllers/UserController.php | UserController |
| application/controllers | foo/UserController | application/controllers/foo/UserController.php | UserController |
| application/controllers | \Foo\UserController | application/controllers/UserController.php | \Foo\UserController |
| application/controllers | foo/\Bar\UserController | application/controllers/foo/UserController.php | \Bar\UserController |

This logic requires to be implemented by child APIs since logic of request depends on STDIN type!
