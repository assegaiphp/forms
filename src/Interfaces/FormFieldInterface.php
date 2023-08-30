<?php

namespace Assegai\Forms\Interfaces;

use Assegai\Validation\Interfaces\IValidationRule as ValidationRuleInterface;
use Stringable;

/**
 * Interface FormFieldInterface. This interface is used to define the methods that a form field must implement.
 * @package Assegai\Forms\Interfaces
 *
 * @template T
 */
interface FormFieldInterface extends Stringable
{
  /**
   * Returns the name of the form field.
   *
   * @return string
   */
  public function getName(): string;

  /**
   * Returns the value of the form field.
   *
   * @return mixed
   */
  public function getValue(): mixed;

  /**
   * Sets the value of the form field.
   *
   * @param mixed $value The value to set.
   * @return void
   */
  public function setValue(mixed $value): void;

  /**
   * Returns the rules of the form field.
   *
   * @return ValidationRuleInterface[] The rules of the form field.
   */
  public function getValidationRules(): array;

  /**
   * Adds a rule to the form field.
   *
   * @param ValidationRuleInterface ...$rules The rules to add.
   * @return void
   */
  public function addValidationRules(ValidationRuleInterface ...$rules): void;

  /**
   * Validates the form field.
   *
   * @return void
   */
  public function validate(): void;

  /**
   * Determines if the form field is valid.
   *
   * @return bool True if the form field is valid, false otherwise.
   */
  public function isValid(): bool;
}