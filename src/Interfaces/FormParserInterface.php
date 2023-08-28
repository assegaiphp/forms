<?php

namespace Assegai\Forms\Interfaces;

/**
 * Interface for form parsers.
 */
interface FormParserInterface
{
  /**
   * Parses a form string into an array of key-value pairs.
   *
   * @param string $form The form string.
   * @return FormInterface The form data.
   */
  public function parse(string $form): FormInterface;

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
   */
  public function getFormFields(string $form): array;

  /**
   * Gets the form boundary. This is used to separate the form fields.
   *
   * @param string $form The form string.
   * @return string The form boundary.
   */
  function getBoundary(string $form): string;
}