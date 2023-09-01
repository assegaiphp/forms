<?php

/**
 * Converts a string to kebab case
 *
 * @param string $string The string to convert.
 * @return string The converted string.
 */
function strtokebab(string $string): string
{
  return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $string));
}

/**
 * Converts a string to pascal case.
 *
 * @param string $string The string to convert.
 * @return string The converted string.
 */
function strtopascal(string $string): string
{
  return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
}

/**
 * Converts a string to camel case.
 *
 * @param string $string The string to convert.
 * @return string The converted string.
 */
function strtocamel(string $string): string
{
  return lcfirst(strtopascal($string));
}

/**
 * Converts a string to kebab case and capitalizes the first letter.
 *
 * @param string $string The string to convert.
 * @return string The converted string.
 */
function strtokebab_ucfirst(string $string): string
{
  return ucfirst(strtokebab($string));
}