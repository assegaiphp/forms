<?php

namespace Assegai\Forms;

use Assegai\Forms\Attributes\AsForm;
use Assegai\Forms\Attributes\AsFormControl;
use Assegai\Forms\Exceptions\FormException;
use Assegai\Forms\Interfaces\FormDataInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use stdClass;

/**
 * Class FormData. Represents a form data object.
 *
 * @package Assegai\Forms
 */
class FormData implements FormDataInterface
{
  /**
   * @var array<array> The form data.
   */
  protected array $data = [];

  /**
   * FormData constructor.
   *
   * @param array $data The form data.
   */
  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value)
    {
      $this->set($key, $value);
    }
  }

  /**
   * @inheritDoc
   */
  public function append(string $key, mixed $value): void
  {
    if (!$this->has($key))
    {
      $this->data[$key] = [];
    }

    $this->data[$key][] = $value;
  }

  /**
   * @inheritDoc
   */
  public function delete(string $key): void
  {
    unset($this->data[$key]);
  }

  /**
   * @inheritDoc
   */
  public function entries(): array
  {
    return $this->data;
  }

  /**
   * @inheritDoc
   */
  public function get(string $key): mixed
  {
    return array_first($this->data[$key] ?? []);
  }

  /**
   * @inheritDoc
   */
  public function getAll(string $key): array
  {
    return $this->data[$key] ?? [];
  }

  /**
   * @inheritDoc
   */
  public function has(string $key): bool
  {
    return isset($this->data[$key]);
  }

  /**
   * @inheritDoc
   */
  public function keys(): array
  {
    return array_keys($this->data);
  }

  /**
   * @inheritDoc
   */
  public function set(string $key, mixed $value): void
  {
    if (!$this->has($key))
    {
      $this->data[$key] = [];
    }

    $this->data[$key][0] = $value;
  }

  /**
   * @inheritDoc
   */
  public function values(): array
  {
    $values = [];

    foreach ($this->data as $datum)
    {
      if (!is_array($datum))
      {
        continue;
      }

      $values[] = array_first($datum);
    }

    return $values;
  }

  /**
   * @inheritDoc
   */
  public function toArray(array $propertyMap = []): array
  {
    return array_combine($this->keys(), $this->values());
  }

  /**
   * @inheritDoc
   */
  public function toObject(?string $templateClass = null): object
  {
    $object = new stdClass();
    $map = $this->toArray();

    if ($templateClass && class_exists($templateClass))
    {
      # Attempt to get the AsForm attribute from the template class.
      $templateClassReflection = new ReflectionClass($templateClass);

      try
      {
        $object = $templateClassReflection->newInstance();
      }
      catch (ReflectionException $e)
      {
        throw new FormException($e->getMessage(), $e->getCode(), $e->getPrevious());
      }
      $asFormAttributeReflection = $templateClassReflection->getAttributes(AsForm::class);

      if (!$asFormAttributeReflection)
      {
        throw new FormException('Template class does not have the AsForm attribute.');
      }

      # If the template class has the AsForm attribute, use it to map the form data to the template class.
      $replacementMap = [];
      $formDataProperties = $templateClassReflection->getProperties(ReflectionProperty::IS_PUBLIC);

      foreach ($formDataProperties as $reflectionProperty)
      {
        $asFormControlAttributeReflection = $reflectionProperty->getAttributes(AsFormControl::class);

        if (!$asFormControlAttributeReflection)
        {
          continue;
        }

        /** @var AsFormControl $asFormControlAttribute */
        $asFormControlAttribute = $asFormControlAttributeReflection[0]->newInstance();
        $name = $asFormControlAttribute->name ?? $reflectionProperty->getName();
        $replacementMap[$reflectionProperty->getName()] =
          $this->get($name) ??
          $reflectionProperty->getValue($object) ??
          $asFormControlAttribute->defaultValue;
      }

      $map = $replacementMap;
    }

    foreach ($map as $key => $value)
    {
      $object->$key = $value;
    }

    return $object;
  }
}