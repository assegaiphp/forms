<?php

namespace Assegai\Tests\Forms\Mocks;

use Assegai\Forms\Attributes\AsForm;
use Assegai\Forms\Attributes\AsFormControl;

#[AsForm]
class MockEntityDto
{
  public function __construct(
    #[AsFormControl(name: 'first_name')]
    public string $firstName = '',
    #[AsFormControl(name: 'last_name')]
    public string $lastName = '',
    #[AsFormControl]
    public string $email = '',
    #[AsFormControl(defaultValue: 'Zambia')]
    public string $country = ''
  ) { }
}