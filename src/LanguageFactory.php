<?php
/**
 * Part of the Joomla Framework Language Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Language;

/**
 * Language package factory
 *
 * @since  1.3.0
 */
class LanguageFactory
{
	/**
	 * Application's default language
	 *
	 * @var    string
	 * @since  1.3.0
	 */
	private $defaultLanguage = 'en-GB';

	/**
	 * Container with a list of loaded classes grouped by object type
	 *
	 * @var    array
	 * @since  1.3.0
	 * @deprecated  2.0  Singleton object storage will no longer be supported
	 */
	private static $loadedClasses = array(
		'language' => array(),
		'stemmer'  => array()
	);

	/**
	 * Get the application's default language
	 *
	 * @return  string
	 *
	 * @since   1.3.0
	 */
	public function getDefaultLanguage()
	{
		return $this->defaultLanguage;
	}

	/**
	 * Returns a language object.
	 *
	 * @param   string   $lang   The language to use.
	 * @param   string   $path   The base path to the language folder.  Unused in 1.x, Language uses JPATH_ROOT constant
	 * @param   boolean  $debug  The debug mode.
	 *
	 * @return  Language
	 *
	 * @since   1.3.0
	 */
	public function getLanguage($lang = null, $path = null, $debug = false)
	{
		$lang = ($lang === null) ? $this->getDefaultLanguage() : $lang;

		if (!isset(self::$loadedClasses['language'][$lang]))
		{
			self::$loadedClasses['language'][$lang] = new Language($lang, $debug);
		}

		return self::$loadedClasses['language'][$lang];
	}

	/**
	 * Get the path to the directory containing the application's language folder
	 *
	 * @return  string
	 *
	 * @since   1.3.0
	 */
	public function getLanguageDirectory()
	{
		return $this->languageDirectory;
	}

	/**
	 * Method to get a stemmer, creating it if necessary.
	 *
	 * @param   string  $adapter  The type of stemmer to load.
	 *
	 * @return  Stemmer
	 *
	 * @since   1.3.0
	 * @throws  \RuntimeException on invalid stemmer
	 */
	public function getStemmer($adapter)
	{
		// Setup the adapter for the stemmer.
		$class = __NAMESPACE__ . '\\Stemmer\\' . ucfirst(trim($adapter));

		// If we've already found this object, no need to try and find it again
		if (isset(self::$loadedClasses['stemmer'][$class]))
		{
			return self::$loadedClasses['stemmer'][$class];
		}

		// Check if a stemmer exists for the adapter.
		if (!class_exists($class))
		{
			// Throw invalid adapter exception.
			throw new \RuntimeException(sprintf('Invalid stemmer type %s', $class));
		}

		$stemmer = new $class;

		// Store the class name to the cache
		self::$loadedClasses['stemmer'][$class] = $stemmer;

		return $stemmer;
	}

	/**
	 * Set the application's default language
	 *
	 * @param   string  $language  Language code for the application's default language
	 *
	 * @return  $this
	 *
	 * @since   1.3.0
	 */
	public function setDefaultLanguage($language)
	{
		$this->defaultLanguage = $language;

		return $this;
	}
}
