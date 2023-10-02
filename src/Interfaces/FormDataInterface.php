<?php

namespace Assegai\Forms\Interfaces;

use Assegai\Collections\ItemList;

/**
 * Interface FormDataInterface. Represents a form data object.
 */
interface FormDataInterface
{
  /**
   * Appends a value to the form data.
   *
   * @param string $name The name of the value to append.
   * @param mixed $value The value to append.
   * @return void
   */
  public function append(string $name, mixed $value): void;

  /**
   * Deletes a value from the form data.
   *
   * @param string $name The name of the value to delete.
   * @return void
   */
  public function delete(string $name): void;

  /**
   * Gets a list of all the entries in the form data.
   *
   * @return ItemList The list of entries.
   */
  public function entries(): ItemList;

  /**
   * Gets a value from the form data.
   *
   * @param string $name The name of the value to get.
   * @return mixed The value if it exists, null otherwise.
   */
  public function get(string $name): mixed;

  /**
   * Gets a list of all the values for the given name.
   *
   * @param string $name The name of the values to get.
   * @return ItemList The list of values.
   */
  public function getAll(string $name): ItemList;

  /**
   * Determines if the form data has a value with the given name.
   *
   * @param string $name The name of the value to check.
   * @return bool True if the form data has the value, false otherwise.
   */
  public function has(string $name): bool;

  /**
   * Gets a list of all the keys in the form data.
   *
   * @return ItemList The list of keys.
   */
  public function keys(): ItemList;

  /**
   * Sets a value in the form data.
   *
   * @param string $name The name of the value to set.
   * @param mixed $value The value to set.
   * @return void
   */
  public function set(string $name, mixed $value): void;

  /**
   * Gets a list of all the values in the form data.
   *
   * @return ItemList The list of values.
   */
  public function values(): ItemList;

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
   */
  public function toObject(?string $templateClass = null): object;
}