<?php

use Assegai\Forms\Exceptions\InvalidFormException;
use Assegai\Forms\FormDecoder;
use Assegai\Forms\Interfaces\FormFieldInterface;

$validFormBoundary = '----------------------------807299579146148994327142';

$validFormData = [
  ['name' => 'website', 'value' => 'http://veda.info'],
  ['name' => 'first_name', 'value' => 'Augusta'],
  ['name' => 'last_name', 'value' => 'Maggio']
];
$validForm = <<<EOT
$validFormBoundary
Content-Disposition: form-data; name="{$validFormData[0]['name']}"

{$validFormData[0]['value']}
$validFormBoundary
Content-Disposition: form-data; name="{$validFormData[1]['name']}"

{$validFormData[1]['value']}
$validFormBoundary
Content-Disposition: form-data; name="{$validFormData[2]['name']}"

{$validFormData[2]['value']}
$validFormBoundary--
EOT;

$invalidForm = <<<EOT
--boundary123
Content-Disposition: form-data; name="field1"

value1
--boundary123
Content-Disposition: form-data; name="field2"

value2
--boundary123--
EOT;

$invalidFormNoBoundary = <<<EOT
Content-Disposition: form-data; name="field1"

value1
Content-Disposition: form-data; name="field2"

value2
--boundary123--
EOT;


it('can decode an encoded form string',
  /**
   * @throws InvalidFormException
   */
  function () use ($validForm, $invalidFormNoBoundary) {
  $formDecoder = new FormDecoder();

  $validForm = $formDecoder->decode($validForm);
  $website = $validForm->getFieldValue('website');
  $firstName = $validForm->getFieldValue('first_name');
  $lastName = $validForm->getFieldValue('last_name');
  $email = $validForm->getFieldValue('email');

  expect($website)->toBe('http://veda.info')
    ->and($firstName)->toBe('Augusta')
    ->and($lastName)->toBe('Maggio')
    ->and($email)->toBeNull();

  try {
    $invalidForm = $formDecoder->decode($invalidFormNoBoundary);
    expect($invalidForm)->toBeNull();
  } catch (InvalidFormException) {
    expect(true)->toBeTrue();
  }
});

it('can test whether a form is valid', function () use ($validForm, $invalidFormNoBoundary) {
  $formDecoder = new FormDecoder();
  expect($formDecoder->isValid($validForm))->toBeTrue()
    ->and($formDecoder->isValid($invalidFormNoBoundary))->toBeFalse();
});

it('can list the form fields as an array of key-value pairs', function () use (
  $validForm,
  $invalidFormNoBoundary,
  $validFormData
) {
  $formDecoder = new FormDecoder();

  try {
    $formFields = $formDecoder->getFormFields($validForm);

    foreach ($formFields as $index => $field)
    {
      expect($field)->toBeInstanceOf(FormFieldInterface::class)
        ->and($field->getName())->toBe($validFormData[$index]['name'])
        ->and($field->getValue())->toBe($validFormData[$index]['value']);
    }
  } catch (InvalidFormException) {
    expect(true)->toBeFalse('An InvalidFormException should NOT have been thrown.');}

  try {
    $formFieldsArray = $formDecoder->getFormFieldsAsArray($validForm);

    expect($formFieldsArray)->toBeArray()
      ->and($formFieldsArray)->toHaveCount(3)
      ->and($formFieldsArray)->toHaveKey('website')
      ->and($formFieldsArray)->toHaveKey('first_name')
      ->and($formFieldsArray)->toHaveKey('last_name')
      ->and($formFieldsArray['website']->getValue())->toBe('http://veda.info')
      ->and($formFieldsArray['first_name']->getValue())->toBe('Augusta')
      ->and($formFieldsArray['last_name']->getValue())->toBe('Maggio');
  } catch (InvalidFormException) {
    expect(true)->toBeFalse();
  }

  try {
    $formFieldsArray = $formDecoder->getFormFields($invalidFormNoBoundary);
    expect($formFieldsArray)->toBeNull();
  } catch (InvalidFormException) {
    expect(true)->toBeTrue();
  }
});

test('get boundary', function () use ($validForm, $invalidFormNoBoundary, $validFormBoundary) {
  $formDecoder = new FormDecoder();
  try {
    $boundary = $formDecoder->getBoundary($validForm);
    expect($boundary)->toBe($validFormBoundary);
  } catch (InvalidFormException) {
    expect(true)->toBeFalse();
  }

  $boundary = $formDecoder->getBoundary($invalidFormNoBoundary);
  expect($boundary)->toBeFalse();
});