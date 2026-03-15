<?php

namespace Assegai\Forms\FormControls;

use Assegai\Forms\Enumerations\FormControlType;
use InvalidArgumentException;

class NumericField extends AbstractFormControl
{
  public function __construct(
    string $name,
    string|float|int|null $value = null,
    array $validationRules = [],
    string $label = '',
    string $placeholder = '',
    string $helpText = '',
    string $id = '',
    string $class = '',
    string $style = ''
  )
  {
    parent::__construct($name, $value, $validationRules, $label, $placeholder, $helpText, FormControlType::NUMERIC, $id, $class, $style);
  }

  public function setValue(mixed $value): void
  {
    if (!is_numeric($value))
    {
      throw new InvalidArgumentException('The value must be numeric.');
    }
    parent::setValue($value);
  }

  /**
   * @inheritDoc
   */
  public function getHelpText(): string
  {
    return $this->helpText;
  }
}
