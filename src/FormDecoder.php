<?php

namespace Assegai\Forms;

use Assegai\Forms\Exceptions\InvalidFormException;
use Assegai\Forms\Interfaces\FormDecoderInterface;
use Assegai\Forms\Interfaces\FormInterface;

/**
 * Class FormDecoder
 * @package Assegai\Forms
 */
class FormDecoder implements FormDecoderInterface
{
  public function __construct(protected ?string $boundary = null)
  {}

  /**
   * @inheritDoc
   */
  public function decode(string $form): FormInterface
  {
    // TODO: Implement decode() method.
    if (!$this->isValid($form))
    {
      throw new InvalidFormException();
    }

    return new Form($this->getFormFields($form));
  }

  /**
   * @inheritDoc
   */
  public function isValid(string $form): bool
  {
    // TODO: Implement isValid() method.
    # Ensure the form is not empty.
    if (empty($form))
    {
      return false;
    }

    # Ensure the form has a boundary.
    if (!$this->getBoundary($form))
    {
      return false;
    }

    # Ensure the form has at least one field.

    # Ensure the form has a valid boundary.

    # Ensure the form has a valid field.

    # Ensure the form has a valid field name.

    # Ensure the form has a valid field value.

    # Ensure the form has a valid field boundary.

    return true;
  }

  /**
   * @inheritDoc
   */
  public function getFormFields(string $form): array
  {
    $boundary = $this->getBoundary($form);

    if ($boundary === false)
    {
      throw new InvalidFormException();
    }

    $rawFields = explode($boundary, $form);

    # Remove the first and last fields.
    $rawFields = array_slice($rawFields, 1, -1);

    # Split the fields into key-value pairs.
    $fields = [];
    foreach ($rawFields as $field) {
      $key = '';
      $value = '';
      if (false !== preg_match('/Content-Disposition: form-data; name="(.*)"/', $field, $matches))
      {
        $key = $matches[1];
      }
      if (strlen($key) === 0)
      {
        continue;
      }

      if (false !== preg_match('/Content-Disposition: form-data;.*\n.*\n(.*)/', $field, $matches))
      {
        $value = $matches[1];
      }
      $fields[$key] = $value;
    }

    return $fields;
  }

  /**
   * @inheritDoc
   */
  function getBoundary(string $form): string|false
  {
    $boundary = substr($form, 0, strpos($form, "\n"));

    if (!$boundary)
    {
      $boundary = substr($form, 0, strpos($form, "\r\n"));
    }

    if (!$boundary || str_starts_with($boundary, 'Content-Disposition: form-data; name="'))
    {
      return false;
    }

    return $boundary;
  }
}