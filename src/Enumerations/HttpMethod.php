<?php

namespace Assegai\Forms\Enumerations;

/**
 * HttpMethod is an enumeration of HTTP methods.
 */
enum HttpMethod: string
{
  case GET = 'GET';
  case POST = 'POST';
  case PUT = 'PUT';
  case PATCH = 'PATCH';
  case DELETE = 'DELETE';
  case OPTIONS = 'OPTIONS';
  case HEAD = 'HEAD';
  case CONNECT = 'CONNECT';
  case TRACE = 'TRACE';

  /**
   * Returns a description of the HTTP method.
   *
   * @return string
   */
  public function description(): string
  {
    return match ($this) {
      self::GET => 'GET (read)',
      self::POST => 'POST (create)',
      self::PUT => 'PUT (update)',
      self::PATCH => 'PATCH (update)',
      self::DELETE => 'DELETE (delete)',
      self::OPTIONS => 'OPTIONS (read)',
      self::HEAD => 'HEAD (read)',
      self::CONNECT => 'CONNECT (read)',
      self::TRACE => 'TRACE (read)',
    };
  }
}
