<?php

namespace Assegai\Forms\Interfaces;

use Assegai\Collections\ItemList;
use Assegai\Forms\Exceptions\FormException;

/**
 * Interface FormDataInterface. Represents a form data object.
 */
interface FormDataInterface
{
  /**
   * Appends a new value onto an existing key inside a FormData object, or adds the key if it does not already exist.
   *
   * @param string $key The key to append the value to.
   * @param mixed $value The value to append.
   * @return void
   */
  public function append(string $key, mixed $value): void;

  /**
   * Deletes a key/value pair from a FormData object.
   *
   * @param string $key The key to delete.
   * @return void
   */
  public function delete(string $key): void;

  /**
   * Gets a list of all the entries in the form data.
   *
   * @return array The list of entries.
   */
  public function entries(): array;

  /**
   * Returns the first value associated with a given key from within a FormData object.
   *
   * @param string $key The key to get the value for.
   * @return mixed The value if it exists, null otherwise.
   */
  public function get(string $key): mixed;

  /**
   * Returns an array of all the values associated with a given key from within a FormData.
   *
   * @param string $key The key to get the values for.
   * @return array The list of values.
   */
  public function getAll(string $key): array;

  /**
   * Determines whether a FormData object contains a certain key.
   *
   * @param string $key The key to check for.
   * @return bool True if the form data has the value, false otherwise.
   */
  public function has(string $key): bool;

  /**
   * Returns a list of all the keys in the form data.
   *
   * @return array The list of keys.
   */
  public function keys(): array;

  /**
   * Sets a new value for an existing key inside a FormData object, or adds the key/value if it does not already exist.
   *
   * @param string $key The key to set the value for.
   * @param mixed $value The value to set.
   * @return void
   */
  public function set(string $key, mixed $value): void;

  /**
   * Returns a list of all the values in the form data.
   *
   * @return array The list of values.
   */
  public function values(): array;

  /**
   * Converts the form data to an array.
   *
   * @param array $propertyMap A map of form data properties to array keys.
   * @return array The array.
   */
  public function toArray(array $propertyMap = []): array;

  /**
   * Converts the form data to an object.
   *
   * @param string|null $templateClass The class to use for the object template.
   * @return object The object.
   * @throws FormException If the template class is invalid. The template class must have the AsForm attribute.
   */
  public function toObject(?string $templateClass = null): object;
}