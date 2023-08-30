<?php

use Assegai\Forms\Enumerations\FormEncodingType;
use Assegai\Forms\Enumerations\HttpMethod;
use Assegai\Forms\Form;

it('can check if a form is submitted', function () {
  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  expect($form->isSubmitted())->toBeFalse();

  $_POST['name'] = 'Shaka';
  $_POST['age'] = 23;

  expect($form->isSubmitted())->toBeTrue();
});

it('can check if a form has a specific value', function () {
  $validFieldName = 'name';
  $invalidFieldName = 'email';

  $form = new Form(method: HttpMethod::POST, selector: '#test-form');
  $form->set($validFieldName, 'Shaka');

  expect($form->has($validFieldName))->toBeTrue()
    ->and($form->has($invalidFieldName))->toBeFalse();
});

it('can retrieve any set field value', function () {
  $validStringValue = 'Shaka';
  $validIntValue = 23;

  $_POST['name'] = $validStringValue;
  $_POST['age'] = $validIntValue;

  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  $name = $form->get('name');
  $age = $form->get('age');

  expect($name)->toBe($validStringValue)
    ->and($age)->toBe($validIntValue)
    ->and($form->get('email'))->toBeNull();
});

it('can set fields to specific values', function () {
  $validStringValue = 'Shaka';
  $validIntValue = 23;

  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  $form->set('name', $validStringValue);
  $form->set('age', $validIntValue);

  expect($form->get('name'))->toBe($validStringValue)
    ->and($form->get('age'))->toBe($validIntValue);
});

it('can list all the field with their values', function () {
  $validStringValue = 'Shaka';
  $validIntValue = 23;

  $_POST['name'] = $validStringValue;
  $_POST['age'] = $validIntValue;

  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  $form->set('name', $validStringValue);
  $form->set('age', $validIntValue);

  expect($form->all())->toBe([
    'name' => $validStringValue,
    'age' => $validIntValue,
  ]);
});

it('can validate the form field against specified rules', function () {
  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  $form->set('name', 'Shaka');
  $form->set('age', 23);

  $form->validate();

  expect($form->isValid())->toBeTrue();
});

it('can list all validation errors', function () {
  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  $form->set('name', 'Shaka');
  $form->set('age', 23);

  $form->validate('name', 'age');
  $errors = $form->getErrors();

  expect($errors)->toBe([
    'name' => [],
    'age' => [],
  ]);
});

it('can be represented as an associative array', function () {
  $validStringValue = 'Shaka';
  $validIntValue = 23;

  $_POST['name'] = $validStringValue;
  $_POST['age'] = $validIntValue;

  $form = new Form(method: HttpMethod::POST, selector: '#test-form');

  $form->set('name', $validStringValue);
  $form->set('age', $validIntValue);

  expect($form->toArray())->toBe([
    'data' => [
      'name' => $validStringValue,
      'age' => $validIntValue,
    ],
    'method' => HttpMethod::POST->value,
    'selector' => '#test-form',
    'encodingType' => FormEncodingType::MULTIPART_FORM_DATA->value,
  ]);
});