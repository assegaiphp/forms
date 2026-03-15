<?php

namespace Assegai\Forms;

use Assegai\Collections\ItemList;
use Assegai\Forms\Exceptions\InvalidFormException;
use Assegai\Forms\FormControls\NumericField;
use Assegai\Forms\FormControls\TextField;
use Assegai\Forms\Interfaces\FormDataInterface;
use Assegai\Forms\Interfaces\FormDecoderInterface;
use Assegai\Forms\Interfaces\FormFieldInterface;

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
  public function decode(string $form): FormDataInterface
  {
    $formData = new FormData();

    foreach ($this->parseFields($form) as $field)
    {
      $formData->append($field['name'], $field['value']);
    }

    return $formData;
  }

  /**
   * @inheritDoc
   */
  public function isValid(string $form): bool
  {
    try
    {
      return !empty($this->parseFields($form));
    }
    catch (InvalidFormException)
    {
      return false;
    }
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
    $fields = [];

    foreach ($this->parseFields($form) as $field)
    {
      $fields[$field['name']] = match (true) {
        is_numeric($field['value']) => new NumericField(name: $field['name'], value: $field['value']),
        default => new TextField(name: $field['name'], value: $field['value']),
      };
    }

    return $fields;
  }

  /**
   * @inheritDoc
   */
  public function getFormFields(string $form): ItemList
  {
    $fields = array_values($this->getFormFieldsAsArray($form));

    return new ItemList(FormFieldInterface::class, $fields);
  }

  /**
   * @inheritDoc
   */
  function getBoundary(string $form): string|false
  {
    if ($this->boundary)
    {
      return $this->boundary;
    }

    $normalizedForm = $this->normalizeLineEndings($form);
    $boundary = trim(strtok($normalizedForm, "\n") ?: '');

    if ($boundary === '' || str_starts_with($boundary, 'Content-Disposition: form-data;'))
    {
      return false;
    }

    return $boundary;
  }

  /**
   * Parses the raw multipart payload into simple name/value pairs.
   *
   * @param string $form The encoded form payload.
   * @return array<int, array{name: string, value: string}>
   * @throws InvalidFormException
   */
  private function parseFields(string $form): array
  {
    if ($form === '')
    {
      throw new InvalidFormException();
    }

    $normalizedForm = $this->normalizeLineEndings($form);
    $boundary = $this->getBoundary($normalizedForm);

    if ($boundary === false)
    {
      throw new InvalidFormException();
    }

    $rawFields = array_slice(explode($boundary, $normalizedForm), 1);
    $fields = [];

    foreach ($rawFields as $rawField)
    {
      $field = ltrim($rawField, "\n");
      $field = rtrim($field, "\n");

      if ($field === '' || $field === '--')
      {
        continue;
      }

      if (str_starts_with($field, '--'))
      {
        $field = ltrim(substr($field, 2), "\n");
      }

      if ($field === '')
      {
        continue;
      }

      $parts = explode("\n\n", $field, 2);

      if (count($parts) < 2)
      {
        throw new InvalidFormException();
      }

      [$headers, $value] = $parts;

      if (false === preg_match('/Content-Disposition:\s*form-data;\s*name="([^"]+)"/', $headers, $matches))
      {
        throw new InvalidFormException();
      }

      $fields[] = [
        'name' => $matches[1],
        'value' => rtrim($value, "\n"),
      ];
    }

    if (empty($fields))
    {
      throw new InvalidFormException();
    }

    return $fields;
  }

  private function normalizeLineEndings(string $value): string
  {
    return str_replace(["\r\n", "\r"], "\n", $value);
  }
}
