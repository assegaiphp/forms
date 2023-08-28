<?php

use Assegai\Forms\Exceptions\InvalidFormException;
use Assegai\Forms\FormDecoder;

$validFormBoundary = '----------------------------807299579146148994327142';

$validForm = <<<EOT
$validFormBoundary
Content-Disposition: form-data; name="website"

http://veda.info
$validFormBoundary
Content-Disposition: form-data; name="first_name"

Augusta
$validFormBoundary
Content-Disposition: form-data; name="last_name"

Maggio
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

  expect($validForm->get('website'))->toBe('http://veda.info')
    ->and($validForm->get('first_name'))->toBe('Augusta')
    ->and($validForm->get('last_name'))->toBe('Maggio')
    ->and($validForm->get('email'))->toBeNull();

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

it('can list the form fields as an array of key-value pairs', function () use ($validForm, $invalidFormNoBoundary) {
  $formDecoder = new FormDecoder();

  try {
    $formFields = $formDecoder->getFormFields($validForm);

    expect($formFields)->toBeArray()
      ->and($formFields)->toHaveCount(3)
      ->and($formFields)->toHaveKey('website')
      ->and($formFields)->toHaveKey('first_name')
      ->and($formFields)->toHaveKey('last_name')
      ->and($formFields['website'])->toBe('http://veda.info')
      ->and($formFields['first_name'])->toBe('Augusta')
      ->and($formFields['last_name'])->toBe('Maggio');
  } catch (InvalidFormException) {
    expect(true)->toBeFalse();
  }

  try {
    $formFields = $formDecoder->getFormFields($invalidFormNoBoundary);
    expect($formFields)->toBeNull();
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