<?php

namespace Assegai\Forms;

use Assegai\Collections\ItemList;
use Assegai\Forms\Exceptions\InvalidFormException;
use Assegai\Forms\FormControls\NumericField;
use Assegai\Forms\FormControls\TextField;
use Assegai\Forms\Interfaces\FormDecoderInterface;
use Assegai\Forms\Interfaces\FormFieldInterface;
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
    if (!str_contains($form, 'Content-Disposition: form-data; name="'))
    {
      return false;
    }

    # Ensure the form has a valid boundary.
    // TODO: Implement boundary validation.

    # Ensure the form has a valid field name and value.
    if (false === preg_match('/Content-Disposition: form-data; name=\"[a-zA-Z][\w\-_]+\"\n\n.*/', $form))
    {
      return false;
    }

    # Ensure the form has a valid field boundary.
    if (false === preg_match('/Content-Disposition: form-data; name=\"[a-zA-][\w\-]+\"\n\n[\w\W]*\-\-/', $form))
    {
      return false;
    }

    return true;
  }

  /**
   * Returns the form fields as a key-value pair array.
   *
   * @param string $form The form to get the fields from.
   * @return array<FormFieldInterface> The form fields.
   * @throws InvalidFormException
   */
  public function getFormFieldsAsArray(string $form): array
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
      $fields[$key] = match(true) {
        is_numeric($value) => new NumericField(name: $key, value: $value),
        default => new TextField(name: $key, value: $value)
      };
    }

    return $fields;
  }

  /**
   * @inheritDoc
   */
  public function getFormFields(string $form): ItemList
  {
    $fields = $this->getFormFieldsAsArray($form);

    return new ItemList(FormFieldInterface::class, $fields);
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