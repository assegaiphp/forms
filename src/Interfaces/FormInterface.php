<?php

namespace Assegai\Forms\Interfaces;

use Assegai\Collections\ItemList;

/**
 * FormInterface is the interface that should be implemented by all form DTOs.
 */
interface FormInterface extends RenderableInterface
{
  /**
   * Determines if the form has been submitted.
   *
   * @return bool True if the form has been submitted, false otherwise.
   */
  public function isSubmitted(): bool;

  /**
   * Determines if the form has a field with the given name.
   *
   * @param string $name The name of the field.
   * @return bool True if the form has the key, false otherwise.
   */
  public function has(string $name): bool;

  /**
   * Gets a value from the form.
   *
   * @param string $fieldName The name of the value to get.
   * @return mixed The value if it exists, null otherwise.
   */
  public function getFieldValue(string $fieldName): mixed;

  /**
   * Sets the value of a form field.
   *
   * @param string $name The name of the field to set.
   * @param mixed $value The value to set.
   * @return void
   */
  public function set(string $name, mixed $value): void;

  /**
   * Adds a field to the form.
   *
   * @param FormFieldInterface $field The field to add.
   * @return void
   */
  public function addField(FormFieldInterface $field): void;

  /**
   * Removes a field from the form.
   *
   * @param string $name The name of the field to remove.
   * @return void
   */
  public function removeField(string $name): void;

  /**
   * Returns a field from the form.
   *
   * @param string $name The name of the field to return.
   * @return FormFieldInterface|null The field.
   */
  public function getField(string $name): ?FormFieldInterface;

  /**
   * Returns all the form fields.
   *
   * @return ItemList<FormFieldInterface> The form fields.
   */
  public function getAllFields(): ItemList;

  /**
   * Returns all the form data.
   *
   * @param bool $asObject True if the data should be returned as an object, false otherwise.
   * @return array|object The form data.
   */
  public function getData(bool $asObject = false): array|object;

  /**
   * Validates the form.
   *
   * @return void
   */
  public function validate(): void;

  /**
   * Determines if the form is valid.
   *
   * @return bool True if the form is valid, false otherwise.
   */
  public function isValid(): bool;

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