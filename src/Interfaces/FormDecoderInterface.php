<?php

namespace Assegai\Forms\Interfaces;

use Assegai\Forms\Exceptions\InvalidFormException;

/**
 * Interface for form parsers.
 */
interface FormDecoderInterface
{
  /**
   * Decodes a form string into an array of key-value pairs.
   *
   * @param string $form The form string.
   * @return FormInterface The form data.
   * @throws InvalidFormException If the form is invalid.
   */
  public function decode(string $form): FormInterface;

  /**
   * Checks if the form is valid.
   *
   * @param string $form The form string.
   * @return bool True if the form is valid, false otherwise.
   */
  public function isValid(string $form): bool;

  /**
   * Gets the form fields.
   *
   * @param string $form The form string.
   * @return array The form fields.
   * @throws InvalidFormException If the form is invalid.
   */
  public function getFormFields(string $form): array;

  /**
   * Gets the form boundary. This is used to separate the form fields.
   *
   * @param string $form The form string.
   * @return string|false The form boundary or false if the form does not have a boundary.
   */
  function getBoundary(string $form): string|false;
}