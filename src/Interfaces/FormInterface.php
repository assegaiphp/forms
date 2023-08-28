<?php

namespace Assegai\Forms\Interfaces;

/**
 * FormInterface is the interface that should be implemented by all form DTOs.
 */
interface FormInterface
{
  /**
   * Determines if the form has a key.
   *
   * @param string $key The key to check.
   * @return bool True if the form has the key, false otherwise.
   */
  public function has(string $key): bool;

  /**
   * Gets a value from the form.
   *
   * @param string $key The key to get.
   * @return mixed The value if it exists, null otherwise.
   */
  public function get(string $key): mixed;

  /**
   * Sets a value in the form.
   *
   * @param string $key The key to set.
   * @param mixed $value The value to set.
   * @return void
   */
  public function set(string $key, mixed $value): void;

  /**
   * Returns all the form values.
   *
   * @return array The form values.
   */
  public function all(): array;

  /**
   * Validates the form.
   *
   * @return bool True if the form is valid, false otherwise.
   */
  public function validate(): bool;

  /**
   * Returns the form errors.
   *
   * @return array The form errors.
   */
  public function getErrors(): array;

  /**
   * Returns the form as an array.
   *
   * @return array The form as an array.
   */
  public function toArray(): array;
}