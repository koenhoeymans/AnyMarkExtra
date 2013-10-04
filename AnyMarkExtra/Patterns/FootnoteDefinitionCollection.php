<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

/**
 * @package AnyMarkExtra
 */
interface FootnoteDefinitionCollection
{
	/**
	 * @param string $marker
	 * @return bool
	 */
	public function definitionExists($marker);
}