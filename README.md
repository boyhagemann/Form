Form
====

With this package you can:

* Generate forms using a fluent interface
* Present data from other models as choice fields like select lists, radio buttons or checkboxes.

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
