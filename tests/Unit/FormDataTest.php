<?php

use Assegai\Forms\Exceptions\FormException;
use Assegai\Forms\FormData;
use Assegai\Tests\Forms\Mocks\MockEntityDto;

it('can append a value to the form data', function () {
    $formData = new FormData();
    $formData->append('name', 'value');
    expect($formData->get('name'))->toBe('value');
});

it('can delete a value from the form data', function () {
    $formData = new FormData();
    $formData->append('name', 'value');
    $formData->delete('name');
    expect($formData->get('name'))->toBeNull();
});

it('can get a list of all the entries in the form data', function () {
    $formData = new FormData();
    $formData->append('name', 'value');
    $formData->append('name', 'value2');
    $formData->append('name2', 'value3');
    $entries = $formData->entries();
    expect(count($entries))->toBe(2);
});

it('can get a value from the form data', function () {
    $formData = new FormData();
    $formData->append('name', 'value');
    expect($formData->get('name'))->toBe('value');
});

it('can get a list of all the values for the given name', function () {
    $formData = new FormData();
    $formData->append('name', 'value');
    $formData->append('name', 'value2');
    $formData->append('name2', 'value3');
    $values = $formData->getAll('name');
    expect(count($values))->toBe(2);
});

it('can check if the form data has a value with the given name', function () {
    $formData = new FormData();
    $formData->append('name', 'value');
    expect($formData->has('name'))->toBeTrue()
        ->and($formData->has('name2'))->toBeFalse();
});

it('can set a value in the form data', function () {
    $formData = new FormData();
    $formData->set('name', 'value');
    expect($formData->get('name'))->toBe('value');
});

it('can get a list of all the values in the form data.', function () {
    $formData = new FormData();
    $formData->set('name', 'value');
    $formData->set('name2', 'value2');
    $formData->set('name2', 'value3');
    $values = $formData->values();
    expect(count($values))->toBe(2);
});

it('can get the form data as an array', function () {
    $formData = new FormData();
    $formData->set('name', 'value');
    $formData->set('name2', 'value2');
    $formData->set('name2', 'value3');
    $array = $formData->toArray();
    expect($array)->toBeArray()
        ->and($array['name'])->toBe('value')
        ->and($array['name2'])->toBe('value3');
});

it('can get the form data as an object of a specific type', /** @throws Exception */ function () {
    require __DIR__ . '/../Mocks/MockEntityDto.php';

    $formData = new FormData();
    $formData->set('name', 'value');
    $formData->set('name2', 'value2');
    $formData->set('name2', 'value3');

    $object = $formData->toObject();
    expect($object)->toBeInstanceOf(stdClass::class)
        ->and($object->name)->toBe('value')
        ->and($object->name2)->toBe('value3');

    try {
      $formData->toObject(stdClass::class);
      throw new Exception('FormException not thrown.');
    }
    catch (FormException) {}

    $firstName = 'Shaka';
    $lastName = 'Zulu';
    $email = 'shaka.zulu@assegaiphp.com';
    $country = 'South Africa';

    $formData = new FormData();
    $formData->set('first_name', $firstName);
    $formData->set('last_name', $lastName);
    $formData->set('email', $email);
    $formData->set('country', $country);

    $object = $formData->toObject(MockEntityDto::class);

    expect($object)->toBeInstanceOf(MockEntityDto::class)
        ->and($object->firstName)->toBe($firstName)
        ->and($object->lastName)->toBe($lastName)
        ->and($object->email)->toBe($email)
        ->and($object->country)->toBe($country);
});