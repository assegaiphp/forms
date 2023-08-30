<?php

namespace Assegai\Forms\Enumerations;

/**
 * @enum string FormControlElementType The type of HTML element to use for the form control.
 */
enum FormControlElementType: string
{
  case INPUT = 'input';
  case TEXTAREA = 'textarea';
  case SELECT = 'select';
  case OPTION = 'option';
  case OPTGROUP = 'optgroup';
  case BUTTON = 'button';
  case LABEL = 'label';
  case FIELDSET = 'fieldset';
  case LEGEND = 'legend';
  case DATALIST = 'datalist';
  case OUTPUT = 'output';
}
