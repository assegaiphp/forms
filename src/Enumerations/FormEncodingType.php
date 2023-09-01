<?php

namespace Assegai\Forms\Enumerations;

enum FormEncodingType: string
{
  case X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';
  case MULTIPART_FORM_DATA = 'multipart/form-data';
  case TEXT_PLAIN = 'text/plain';
  case TEXT_XML = 'text/xml';
  case JSON = 'application/json';
}
