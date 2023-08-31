<?php

namespace Assegai\Forms\FormControls;

use Assegai\Forms\Enumerations\FormControlType;
use Assegai\Forms\Interfaces\FormFieldInterface;
use Assegai\Validation\Validator;
use ReflectionException;

abstract class AbstractFormControl implements FormFieldInterface
{
  /**
   * @var Validator $validator The validator of the form field.
   */
  protected Validator $validator;

  /**
   * AbstractFormControl constructor.
   *
   * @param string $name The name of the form field.
   * @param mixed|null $value The value of the form field.
   * @param string[] $validationRules The validation rules of the form field.
   * @param string $label The label of the form field.
   * @param string $placeholder The placeholder of the form field.
   * @param string $helpText The help text of the form field.
   * @param FormControlType $type The type of the form field.
   * @param string $id The id of the form field.
   * @param string $class The class of the form field.
   * @param string $style The style of the form field.
   */
  public function __construct(
    protected string                 $name,
    protected mixed                  $value = null,
    protected array                  $validationRules = [],
    protected string                 $label = '',
    protected string                 $placeholder = '',
    protected string                 $helpText = '',
    protected FormControlType        $type = FormControlType::TEXT,
    protected string                 $id = '',
    protected string                 $class = '',
    protected string                 $style = '',
  )
  {
    $this->validator = new Validator();
  }

  /**
   * @inheritDoc
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @inheritDoc
   */
  public function getValue(): mixed
  {
    return $this->value;
  }

  /**
   * @inheritDoc
   * @throws ReflectionException
   */
  public function setValue(mixed $value): void
  {
    $this->value = $value;
    $this->validate();
  }

  /**
   * @inheritDoc
   */
  public function isValid(): bool
  {
    return empty($this->validator->getErrors());
  }

  /**
   * @inheritDoc
   */
  public function __toString(): string
  {
    $validName = strtokebab($this->name);

    return sprintf(
      '<div class="%s"><label for="%s">%s</label><input type="text" name="%s" id="%s" value="%s" placeholder="%s" /></div>',
      $this->getCSSClass(),
      $validName,
      $this->label,
      $validName,
      $validName,
      $this->value,
      $this->placeholder
    );
  }

  /**
   * Returns the label of the form field.
   *
   * @return string The label of the form field.
   */
  public function getLabel(): string
  {
    return $this->label ?? $this->name;
  }

  public function getPlaceholder(): string
  {
    return $this->placeholder;
  }

  /**
   * Returns the help text of the form field.
   *
   * @return string The help text of the form field.
   */
  public abstract function getHelpText(): string;

  /**
   * Returns the name of the form field.
   *
   * @return string The name of the form field.
   */
  public function getRuleString(): string
  {
    return implode('|', $this->validationRules);
  }

  /**
   * @inheritDoc
   * @throws ReflectionException
   */
  public function validate(): void
  {
    $this->validator->validate($this->value, $this->getRuleString());
  }

  /**
   * @inheritDoc
   */
  public function getValidationRules(): array
  {
    return $this->validationRules;
  }

  /**
   * @inheritDoc
   */
  public function addValidationRules(string ...$rules): void
  {
    $this->validationRules = [...$this->validationRules, ...$rules];
    $this->validator->addAllRules($rules);
  }

  /**
   * Returns the element id of the form field.
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * Returns the style of the form field.
   */
  public function getStyle(): string
  {
    return $this->style;
  }

  /**
   * Returns the CSS class of the form field.
   *
   * @return string The CSS class of the form field.
   */
  public function getCSSClass(): string
  {
    return sprintf("form-control %s", $this->class);
  }

  /**
   * @inheritDoc
   */
  public function getErrors(): array
  {
    return $this->validator->getErrors();
  }
}