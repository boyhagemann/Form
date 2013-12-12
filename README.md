Form
====

[![Build Status](https://travis-ci.org/boyhagemann/Form.png?branch=master)](https://travis-ci.org/boyhagemann/Form)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/boyhagemann/Form/badges/quality-score.png?s=8103612755c7470eec131897dbc93d6c7236e0cb)](https://scrutinizer-ci.com/g/boyhagemann/Form/)
[![Code Coverage](https://scrutinizer-ci.com/g/boyhagemann/Form/badges/coverage.png?s=ecb4b7677b38abd8279c89dfdf469c2fffdd12a4)](https://scrutinizer-ci.com/g/boyhagemann/Form/)

With this package you can:

* Generate forms using a fluent interface
* Present data from other models as choice fields like select lists, radio buttons or checkboxes.
* Render a whole form at once or render just one element in your view script.
* Use it in conjunction with the [Crud package] (http://github.com/boyhagemann/Crud) for full admin power!

## Install

Use [Composer] (http://getcomposer.org) to install the package into your application
```json
require {
    "boyhagemann/form": "dev-master"
}
```

Then add the following line in app/config/app.php:
```php
...
"Boyhagemann\Form\FormServiceProvider"
...
```

## Example usage

```php
<?php

use Boyhagemann\Crud\FormBuilder;


$fb = App::make('FormBuilder');

$fb->text('title')->label('Title')->rules('required|alpha');
$fb->textarea('body')->label('Body');
$fb->radio('online')->choices(array('no', 'yes'))->label('Show online?');
        
// You can use a fluent typing style
$fb->modelSelect('category_id')
   ->model('Category')
   ->label('Choose a category')
   ->query(function($q) {
     $q->orderBy('title');
   });
   
// Change an element
$fb->get('title')->label('What is the title?');
   
```

## Export and import
The FormBuilder can be exported as an array with the toArray method. 
This array can be stored as a config file.
```php
// Get the form as a config and store it in a file or session
$config = $fb->toArray();

// Then import it back again later to get the exact form
$fb->fromArray($config);
```

## Events
There are several events triggered while building the form:

`formbuilder.build.form.pre`
Allows you to alter anything on the formbuilder just before the build process is starting.

`formbuilder.build.form.post`
After building the form you can hook into the formbuilder to perform other things. 
Or you can interact with the generated form html.

`formbuilder.build.element.pre`
Just before an element is build, you can alter the formbuilder instance or the element object itself.

`formbuilder.build.element.post`
After the element is build you can do things with the formbuilder instance or with the created element html.

# Subscribers
Subscribers are a combination of events and solve common problems or help with user scenarios.
They are added to your application with a single line of code.
Preferably, you should have a `events.php` file next to your `filters.php` and `routes.php` files. 
Here are the included subscribers:

### FillFormWithErrorsFromSession
When this subscribers is registered, you can read the possible errors from the session. 
To use it, simply add the following line to your application.
```php
Event::register('Boyhagemann\Form\Subscriber\FillFormWithErrorsFromSession');
```

### SaveFormStateInSession
When you make multipage forms, or you want to go a different page and then back to your form, you should probably
store the form values in a session. 
This subscriber does it for you. 
Add this line to your application:
```php
Event::register('Boyhagemann\Form\Subscriber\SaveFormStateInSession');
```

