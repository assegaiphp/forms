<?php

namespace Assegai\Forms;

use Assegai\Collections\ItemList;
use Assegai\Forms\Enumerations\FormEncodingType;
use Assegai\Forms\Enumerations\HttpMethod;
use Assegai\Forms\Exceptions\FormException;
use Assegai\Forms\Exceptions\InvalidFormException;
use Assegai\Forms\FormControls\NumericField;
use Assegai\Forms\FormControls\TextField;
use Assegai\Forms\Interfaces\FormDecoderInterface;
use Assegai\Forms\Interfaces\FormFieldInterface;
use Assegai\Forms\Interfaces\FormInterface;
use Exception;

/**
 * Class Form. This class is used to create a form.
 */
class Form implements FormInterface
{
  /**
   * @var array<Exception[]> A list of form errors.
   */
  protected array $errors = [];
  /**
   * @var ItemList<FormFieldInterface> The form fields.
   */
  protected ItemList $fields;

  /**
   * Constructs a new form.
   *
   * @param ItemList<FormFieldInterface>|null $fields The form fields.
   * @param HttpMethod $method The form method.
   * @param string $selector The form selector.
   * @param FormEncodingType $encodingType The form encoding type. Defaults to FormEncodingType::MULTIPART_FORM_DATA.
   * @param FormDecoderInterface $decoder The form decoder. Defaults to new FormDecoder().
   * @throws InvalidFormException
   */
  public function __construct(
    ?ItemList $fields = null,
    protected HttpMethod $method = HttpMethod::POST,
    protected string $selector = '',
    protected FormEncodingType $encodingType = FormEncodingType::MULTIPART_FORM_DATA,
    protected FormDecoderInterface $decoder = new FormDecoder()
  )
  {
    $this->fields = $fields ?? new ItemList(FormFieldInterface::class);

    if (!$this->isValid())
    {
      throw new InvalidFormException();
    }

    if ($this->isSubmitted())
    {
      # Hydrate the form with the submitted data.
      $data = match($this->method) {
        HttpMethod::GET => $_GET,
        HttpMethod::POST => $_POST,
        HttpMethod::PUT,
        HttpMethod::PATCH => ($this->decoder->decode(file_get_contents('php://input')))->all(),
        default => [],
      };

      $this->createFormFieldsFromSubmittedData($data);
    }
  }

  /**
   * @inheritDoc
   */
  public function isSubmitted(): bool
  {
    return match ($this->method) {
      HttpMethod::GET => !empty($_GET),
      HttpMethod::POST => !empty($_POST),
      HttpMethod::PUT,
      HttpMethod::PATCH => false !== file_get_contents('php://input'),
      default => false,
    };
  }

  /**
   * @inheritDoc
   */
  public function has(string $name): bool
  {
    return $this->fields->exists(function (FormFieldInterface $item) use ($name) { return $item->getName() === $name; });
  }

  /**
   * @inheritDoc
   */
  public function getFieldValue(string $fieldName): mixed
  {
    $field = $this->fields->find(function ($item) use ($fieldName) { return $item->getName() === $fieldName; });
    return $field?->getValue() ?? null;
  }

  /**
   * @inheritDoc
   */
  public function set(string $name, mixed $value): void
  {
    $newField = match (gettype($value)) {
      "double",
      'integer' => new NumericField($name, $value),
      default => new TextField($name, $value),
    };
    $this->fields->add($newField);
    $this->errors[$name] ??= [];
  }

  /**
   * @inheritDoc
   */
  public function all(): array
  {
    $fieldsMap = [];

    foreach ($this->getAllFields() as $field)
    {
      $fieldsMap[$field->getName()] = $field->getValue();
    }

    return $fieldsMap;
  }

  /**
   * @inheritDoc
   */
  public function validate(): void
  {
    /** @var FormFieldInterface $field */
    foreach ($this->fields as $field)
    {
      $field->validate();
      $this->errors[$field->getName()] = [...$field->getErrors()];
    }
  }

  /**
   * @inheritDoc
   */
  public function isValid(): bool
  {
    foreach ($this->errors as $fieldErrors)
    {
      if (!empty($fieldErrors))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * @inheritDoc
   */
  public function getErrors(?string $key = null): array
  {
    return $this->errors[$key] ?? $this->errors;
  }

  /**
   * @inheritDoc
   */
  public function toArray(): array
  {
    return [
      'data' => $this->all(),
      'method' => $this->method->value,
      'selector' => $this->selector,
      'encodingType' => $this->encodingType->value,
    ];
  }

  /**
   * @inheritDoc
   */
  public function addField(FormFieldInterface $field): void
  {
    $this->fields->add($field);
  }

  /**
   * @inheritDoc
   */
  public function removeField(string $name): void
  {
    $this->fields->remove($name);
  }

  /**
   * @inheritDoc
   */
  public function getField(string $name): ?FormFieldInterface
  {
    return $this->fields->find(function ($item) use ($name) { $item->getName() === $name; }) ?? null;
  }

  /**
   * @inheritDoc
   */
  public function getAllFields(): ItemList
  {
    return $this->fields;
  }

  /**
   * @inheritDoc
   */
  public function render(): string
  {
    // TODO: Implement render() method.
    throw new Exception('Not implemented.');
  }

  /**
   * Creates the form fields from the submitted data.
   *
   * @param array $data The submitted data.
   * @return void
   */
  private function createFormFieldsFromSubmittedData(array $data): void
  {
    foreach ($data as $name => $value)
    {
      $this->set($name, $value);
    }
  }
}