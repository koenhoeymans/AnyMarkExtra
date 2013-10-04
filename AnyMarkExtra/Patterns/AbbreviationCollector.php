<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

/**
 * @package AnyMarkExtra
 */
interface AbbreviationCollector
{
	/**
	 * Get a list of all abbreviations as an associative array.
	 * 
	 * @return array
	 */
	public function getAbbreviations();

	/**
	 * Return the definition for an abbreviation.
	 * 
	 * @param string $name
	 * @return string
	 */
	public function getDefinition($name);
}