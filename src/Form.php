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
   * @var FormData The form data.
   */
  protected FormData $data;

  /**
   * Constructs a new form.
   *
   * @param ItemList<FormFieldInterface>|null $fields The form fields.
   * @param HttpMethod $method The form method.
   * @param string $selector The form selector.
   * @param FormEncodingType $encodingType The form encoding type. Defaults to FormEncodingType::MULTIPART_FORM_DATA.
   * @param FormDecoderInterface $decoder The form decoder. Defaults to new FormDecoder().
   * @throws InvalidFormException
   * @throws FormException
   */
  public function __construct(
    ?ItemList $fields = null,
    protected HttpMethod $method = HttpMethod::POST,
    protected string $selector = '',
    protected FormEncodingType $encodingType = FormEncodingType::MULTIPART_FORM_DATA,
    protected FormDecoderInterface $decoder = new FormDecoder(),
    protected ?string $template = null,
  )
  {
    $this->fields = $fields ?? new ItemList(FormFieldInterface::class);
    $this->data = new FormData();

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
        HttpMethod::PATCH => $this->template
          ? $this->decoder->decode(file_get_contents('php://input'))->toObject($this->template)
          : $this->decoder->decode(file_get_contents('php://input'))->toArray(),
        default => [],
      };

      if (is_object($data))
      {
        $data = (array)$data;
      }

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
      HttpMethod::PATCH => (($payload = file_get_contents('php://input')) !== false) && $payload !== '',
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
    $existingField = $this->getField($name);

    if ($existingField)
    {
      $existingField->setValue($value);
      $this->errors[$name] = [...$existingField->getErrors()];
      $this->data->set($name, $existingField->getValue());
      return;
    }

    $newField = match (true) {
      is_int($value),
      is_float($value),
      is_string($value) && is_numeric($value) => new NumericField($name, $value),
      default => new TextField($name, $value),
    };

    $this->addField($newField);
  }

  /**
   * @inheritDoc
   */
  public function getData(bool $asObject = false): array|object
  {
    $fieldsMap = [];

    foreach ($this->getAllFields() as $field)
    {
      $fieldsMap[$field->getName()] = $field->getValue();
    }

    if ($asObject)
    {
      return (new FormData($fieldsMap))->toObject($this->template);
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
    if ($key === null)
    {
      return $this->errors;
    }

    return $this->errors[$key] ?? [];
  }

  /**
   * @inheritDoc
   */
  public function toArray(): array
  {
    return [
      'data' => $this->getData(),
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
    if ($existingField = $this->getField($field->getName()))
    {
      $this->fields->remove($existingField);
    }

    $this->fields->add($field);
    $this->errors[$field->getName()] = [...$field->getErrors()];
    $this->data->set($field->getName(), $field->getValue());
  }

  /**
   * @inheritDoc
   */
  public function removeField(string $name): void
  {
    if ($field = $this->getField($name))
    {
      $this->fields->remove($field);
    }

    unset($this->errors[$name]);
    $this->data->delete($name);
  }

  /**
   * @inheritDoc
   */
  public function getField(string $name): ?FormFieldInterface
  {
    return $this->fields->find(
      function (FormFieldInterface $item) use ($name) {
        return $item->getName() === $name;
      }
    ) ?? null;
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
    $attributes = [
      'method' => $this->method->value,
      'action' => '',
      'enctype' => $this->encodingType->value,
    ];

    if ($this->selector !== '')
    {
      if (str_starts_with($this->selector, '#'))
      {
        $attributes['id'] = substr($this->selector, 1);
      }
      elseif (str_starts_with($this->selector, '.'))
      {
        $attributes['class'] = trim(str_replace('.', ' ', $this->selector));
      }
      else
      {
        $attributes['action'] = $this->selector;
      }
    }

    $formAttributes = implode(
      ' ',
      array_map(
        fn(string $name, string $value) => sprintf('%s="%s"', $name, htmlspecialchars($value, ENT_QUOTES, 'UTF-8')),
        array_keys($attributes),
        array_values($attributes),
      )
    );
    $fields = implode('', array_map(
      static fn(FormFieldInterface $field) => (string)$field,
      iterator_to_array($this->fields),
    ));

    return sprintf('<form %s>%s</form>', $formAttributes, $fields);
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
