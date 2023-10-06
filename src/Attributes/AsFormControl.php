<?php

namespace Assegai\Forms\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class AsFormControl
{
  /**
   * AsFormControl constructor.
   *
   * @param string|null $name
   * @param mixed|null $defaultValue
   * @param array $validators
   */
  public function __construct(
    public ?string $name = null,
    public mixed $defaultValue = null,
    public array $validators = [],
  )
  {
  }
}