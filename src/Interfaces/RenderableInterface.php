<?php

namespace Assegai\Forms\Interfaces;

/**
 * Interface for renderable components.
 */
interface RenderableInterface
{
  /**
   * Renders the component.
   *
   * @return string The rendered component.
   */
  public function render(): string;
}