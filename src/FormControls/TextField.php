<?php

namespace Assegai\Forms\FormControls;

use Assegai\Validation\Validator;
use ReflectionException;

/**
 * Class TextField. This class is used to create a text field.
 */
class TextField extends AbstractFormControl
{
  /**
   * @inheritDoc
   */
  public function __construct(
    string $name,
    mixed $value = null,
    array $validationRules = [],
    string $label = '',
    string $placeholder = '',
    string $helpText = ''
  )
  {
    parent::__construct($name, $value, $validationRules, $label, $placeholder, $helpText);
  }

  /**
   * @inheritDoc
   */
  public function getHelpText(): string
  {
    return $this->helpText;
  }
}