<?php

namespace Assegai\Forms\Interfaces;

use Assegai\Validation\Interfaces\IValidationRule;

/**
 * FormValidatorInterface is the interface that should be implemented by all form validators.
 */
interface FormValidatorInterface
{
  /**
   * Validates a form.
   *
   * @param FormInterface $form The form to validate.
   * @return bool True if the form is valid, false otherwise.
   */
  public function validate(FormInterface $form): bool;

  /**
   * Returns the form errors.
   *
   * @return array The form errors.
   */
  public function getErrors(): array;

  /**
   * Sets the validation rules.
   *
   * @param IValidationRule ...$rules The validation rules.
   * @return void
   */
  public function setRules(IValidationRule ...$rules): void;
}