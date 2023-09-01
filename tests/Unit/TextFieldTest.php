<?php

use Assegai\Forms\FormControls\TextField;

it('can return its name', function () {
  $validName = 'Shaka';
  $invalidName = 'Zulu';

  $field = new TextField(name: $validName);

  expect($field->getName())->toBe($validName)
    ->and($field->getLabel())->not()->toBe($invalidName);
});

it('can return its label', function () {
  $validLabel = 'Shaka';
  $invalidLabel = 'Zulu';

  $field = new TextField(name: 'name', label: $validLabel);

  expect($field->getLabel())->toBe($validLabel)
    ->and($field->getLabel())->not()->toBe($invalidLabel);
});

it('can return its placeholder', function () {
  $validPlaceholder = 'Shaka';
  $invalidPlaceholder = 'Zulu';

  $field = new TextField(name: 'name', placeholder: $validPlaceholder);

  expect($field->getPlaceholder())->toBe($validPlaceholder)
    ->and($field->getPlaceholder())->not()->toBe($invalidPlaceholder);
});

it('can return its help text', function () {
  $validHelpText = 'Shaka';
  $invalidHelpText = 'Zulu';

  $field = new TextField(name: 'name', helpText: $validHelpText);

  expect($field->getHelpText())->toBe($validHelpText)
    ->and($field->getHelpText())->not()->toBe($invalidHelpText);
});

it('can set and return its value', /** @throws ReflectionException */ function () {
  $firstValue = 'Shaka';
  $secondValue = 'Zulu';

  $field = new TextField(name: 'name', value: $firstValue);

  expect($field->getValue())->toBe($firstValue)
    ->and($field->getValue())->not()->toBe($secondValue);

  $field->setValue($secondValue);

  expect($field->getValue())->toBe($secondValue)
    ->and($field->getValue())->not()->toBe($firstValue);
});

it('can return its validation rules', function () {
  $validRules = [
    'required',
    'min:3',
    'max:10',
  ];

  $field = new TextField(name: 'name', validationRules: $validRules);

  expect($field->getValidationRules())->toBe($validRules);
});

it('can add validation rules', function () {
  $expectedRules = [
    'required',
    'min:3',
    'max:10',
  ];

  $field = new TextField(name: 'name');

  $field->addValidationRules(...$expectedRules);

  $actualRules = $field->getValidationRules();
  expect($actualRules)->toBe($expectedRules);
});

it('can validate itself', /** @throws ReflectionException */ function () {
  $validValue = 'Shaka';
  $invalidValue = 100;
  $validationRules = [
    'required',
    'minLength:3',
    'maxLength:10',
  ];

  $field = new TextField(name: 'name', value: $validValue, validationRules: $validationRules);
  $field->validate();

  $validationResult = $field->isValid();
  expect($validationResult)->toBeTrue();

  $field->setValue($invalidValue);
  $validationResult = $field->isValid();
  expect($validationResult)->toBeFalse();
});