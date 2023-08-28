<div align="center" style="padding-bottom: 48px">
    <a href="https://assegaiphp.com/" target="blank"><img src="https://assegaiphp.com/images/logos/logo-cropped.png" width="200" alt="Assegai Logo"></a>
</div>

<p style="text-align: center">A progressive <a href="https://php.net">PHP</a> framework for building effecient and scalable server-side applications.</p>

## Description

Assegai is a framework for building efficient, scalable <a href="https://php.net" target="blank">PHP</a> server-side applications. It uses modern PHP (~ PHP 8.1) and combines elements of OOP (Object Oriented Programming) and FP (Functional Programming).

## Overview

The AssegaiPHP Forms Library is a powerful and flexible tool for managing HTML forms submitted using POST, PUT, or PATCH requests. This library is designed to simplify the process of handling form data, validation, and submission in PHP web applications. It provides a clean and intuitive interface for creating, processing, and validating forms, making it easier for developers to build robust and secure web applications.

## Features

- **Form Creation:** Easily create HTML forms programmatically using a simple and intuitive syntax.
- **Form Fields:** Support for various types of form fields such as text fields, checkboxes, radio buttons, dropdowns, and more.
- **Data Binding:** Automatically populate form fields with data from your models or arrays.
- **Validation:** Define validation rules for form fields and perform server-side validation effortlessly.
- **Error Handling:** Automatically display validation errors next to the corresponding form fields.
- **CSRF Protection:** Built-in CSRF token generation and verification to enhance security.
- **File Uploads:** Handle file uploads with ease and integrate them seamlessly into your forms.
- **Customization:** Highly customizable rendering, theming, and extending capabilities.
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

   $form = new Form('contact_form', 'POST', '/submit');
   ```

2. **Add Form Fields:**

   ```php
   $form->addText('name', 'Name')->required();
   $form->addEmail('email', 'Email')->required()->email();
   $form->addTextarea('message', 'Message')->required();
   ```

3. **Process Form Submission:**

   ```php
   if ($form->isSubmitted() && $form->isValid()) {
       // Process the form data
       $data = $form->getData();
       // ...
   }
   ```

4. **Render the Form:**

   ```php
   echo $form->render();
   ```

For more detailed usage and customization options, please refer to the [Documentation](docs/README.md).

## Contributing

We welcome contributions from the community! If you'd like to contribute to the AssegaiPHP Forms Library, please follow our [Contribution Guidelines](CONTRIBUTING.md).

## License

The AssegaiPHP Forms Library is open-source software licensed under the [MIT License](LICENSE).

---