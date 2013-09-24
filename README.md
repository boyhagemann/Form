Form
====

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


