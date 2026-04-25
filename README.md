<div align="center" style="padding-bottom: 48px">
    <a href="https://assegaiphp.com/" target="blank"><img src="https://assegaiphp.com/images/logos/logo-cropped.png" width="200" alt="Assegai Logo"></a>
</div>

<p align="center">
  <a href="https://github.com/assegaiphp/forms/releases"><img alt="Latest release" src="https://img.shields.io/github/v/release/assegaiphp/forms?display_name=tag&sort=semver&style=flat-square"></a>
  <a href="https://github.com/assegaiphp/forms/actions/workflows/php.yml"><img alt="Tests" src="https://img.shields.io/github/actions/workflow/status/assegaiphp/forms/php.yml?branch=main&label=tests&style=flat-square"></a>
  <img alt="PHP 8.4+" src="https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat-square&logo=php&logoColor=white">
  <a href="https://github.com/assegaiphp/forms/blob/main/LICENSE"><img alt="License" src="https://img.shields.io/github/license/assegaiphp/forms?style=flat-square"></a>
  <img alt="Status active" src="https://img.shields.io/badge/status-active-10b981?style=flat-square">
</p>

<p style="text-align: center">A progressive <a href="https://php.net">PHP</a> framework for building effecient and scalable server-side applications.</p>

## Description
Assegai is a framework for building efficient, scalable <a href="https://php.net" target="blank">PHP</a> server-side applications. It uses modern PHP (PHP 8.4+) and combines elements of OOP (Object Oriented Programming) and FP (Functional Programming).
## Overview
The AssegaiPHP Forms Library is a powerful and flexible tool for managing HTML forms submitted using POST, PUT, or PATCH requests. This library is designed to simplify the process of handling form data, validation, and submission in PHP web applications. It provides a clean and intuitive interface for creating, processing, and validating forms, making it easier for developers to build robust and secure web applications.

## Contribution workflow

For commit and pull request conventions in this repo, see:

- [docs/commit-and-pr-guidelines.md](./docs/commit-and-pr-guidelines.md)

Git hooks for this repository live in [`.githooks`](./.githooks). Running `composer install` or `composer update`
will automatically configure `core.hooksPath` for this clone so the committed `pre-push` checks are used. If you
need to apply the hook configuration manually, run:

```bash
composer run hooks:install
```

## Features
- **Form Creation:** Easily create HTML forms programmatically using a simple and intuitive syntax.
- **Form Fields:** Support for various types of form fields such as text fields, numeric fields, and more.
- **Data Binding:** Automatically populate form fields with data from your models or arrays.
- **Validation:** Define validation rules for form fields and perform server-side validation effortlessly.
- **Error Handling:** Automatically retrieve validation errors for form fields.
- **Customization:** Highly customizable rendering and extending capabilities.
- **Compatibility:** Works well with modern PHP applications and follows best practices.
## Installation
You can install the AssegaiPHP Forms Library using [Composer](https://getcomposer.org/):
```bash
composer require assegaiphp/forms
```
## Quick Start
1. **Create a Form:**
   ```php
   use Assegai\Forms\Form;
   use Assegai\Forms\Enumerations\HttpMethod;

   $form = new Form(
       method: HttpMethod::POST,
       selector: '#contact-form'
   );
   ```
2. **Add Form Fields:**
   ```php
   $form->set('name', '');
   $form->set('email', '');
   $form->set('message', '');
   ```
3. **Process Form Submission:**
   ```php
   if ($form->isSubmitted()) {
       // Validate the form
       $form->validate();
       if ($form->isValid()) {
           // Process the form data
           $data = $form->getData();
           // ...
       } else {
           $errors = $form->getErrors();
           // Handle validation errors
       }
   }
   ```
4. **Render the Form:**
   ```php
   echo $form->render();
   ```
## Advanced Usage
### Adding Validation Rules
```php
use Assegai\Forms\Form;
use Assegai\Forms\Enumerations\HttpMethod;
use Assegai\Forms\FormControls\TextField;

$form = new Form(method: HttpMethod::POST, selector: '#user-form');

// Create a field with validation rules
$nameField = new TextField('name', '', ['required', 'min:3']);
$form->addField($nameField);

// Or add fields and set validation rules later
$form->set('email', '');
$emailField = $form->getField('email');
$emailField->addValidationRules('required', 'email');
```
### Getting Form Data
```php
// Get data as an associative array
$data = $form->getData();

// Get data as a stdClass object
$dataObject = $form->getData(asObject: true);
```
### Working with Individual Fields
```php
// Check if a field exists
if ($form->has('name')) {
    // Get a specific field value
    $name = $form->getFieldValue('name');

    // Get the field object
    $nameField = $form->getField('name');

    // Remove a field
    $form->removeField('name');
}
```
### Form HTTP Methods
The form supports multiple HTTP methods:
```php
use Assegai\Forms\Enumerations\HttpMethod;

// POST forms
$postForm = new Form(method: HttpMethod::POST, selector: '#form');

// GET forms
$getForm = new Form(method: HttpMethod::GET, selector: '#search');

// PUT/PATCH forms for updates
$updateForm = new Form(method: HttpMethod::PUT, selector: '#update-form');
$patchForm = new Form(method: HttpMethod::PATCH, selector: '#patch-form');
```
### Form Selector
The selector parameter can be used to set the form's ID, CSS class, or action URL:
```php
// Set the form ID
$form = new Form(selector: '#my-form');

// Set the form CSS classes
$form = new Form(selector: '.form-class1.form-class2');

// Set the form action URL
$form = new Form(selector: '/submit-form');
```
For more detailed usage and customization options, please refer to the [Documentation](docs/README.md).
## Contributing
We welcome contributions from the community! If you'd like to contribute to the AssegaiPHP Forms Library, please follow our [Contribution Guidelines](CONTRIBUTING.md).
## License
The AssegaiPHP Forms Library is open-source software licensed under the [MIT License](LICENSE).
---
