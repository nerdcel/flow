<?php
declare(ENCODING = 'utf-8');
namespace F3::FLOW3::Reflection;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package FLOW3
 * @subpackage Reflection
 * @version $Id:F3::FLOW3::Reflection::DocCommentParser.php 467 2008-02-06 19:34:56Z robert $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */

/**
 * A little parser which creates tag objects from doc comments
 *
 * @package FLOW3
 * @subpackage Reflection
 * @version $Id:F3::FLOW3::Reflection::DocCommentParser.php 467 2008-02-06 19:34:56Z robert $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class DocCommentParser {

	/**
	 * @var string The description as found in the doc comment
	 */
	protected $description = '';

	/**
	 * @var array An array of tag names and their values (multiple values are possible)
	 */
	protected $tags = array();

	/**
	 * Parses the given doc comment and saves the result (description and
	 * tags) in the parser's object. They can be retrieved by the
	 * getTags() getTagValues() and getDescription() methods.
	 *
	 * @param  string $docComment: A doc comment as returned by the reflection getDocComment() method
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function parseDocComment($docComment) {
		$this->description = '';
		$this->tags = array();

		$lines = explode(chr(10), $docComment);
		foreach ($lines as $line) {
			if (strlen($line) > 0 && strpos($line, '@') !== FALSE) {
				$this->parseTag(substr($line, strpos($line, '@')));
			} else if (count($this->tags) == 0) {
				$this->description .= $line . chr(10);
			}
		}
	}

	/**
	 * Returns the tags which have been previously parsed
	 *
	 * @return array Array of tag names and their (multiple) values
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getTagsValues() {
		return $this->tags;
	}

	/**
	 * Returns the values of the specified tag. The doc comment
	 * must be parsed with parseDocComment() before tags are
	 * available.
	 *
	 * @param string $tagName: The tag name to retrieve the values for
	 * @return array The tag's values
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getTagValues($tagName) {
		if (!$this->isTaggedWith($tagName)) throw new RuntimeException('Tag "' . $tagName . '" does not exist.', 1169128255);
		return $this->tags[$tagName];
	}

	/**
	 * Checks if a tag with the given name exists
	 *
	 * @param  string $tagName: The tag name to check for
	 * @return boolean TRUE the tag exists, otherwise FALSE
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function isTaggedWith($tagName) {
		return (isset($this->tags[$tagName]));
	}

	/**
	 * Parses a line of a doc comment for a tag and its value.
	 * The result is stored in the
	 *
	 * @param  string $line: A line of a doc comment which starts with an @-sign
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	protected function parseTag($line) {
		$tagAndValue = preg_split('/\s/', $line, 2);
		$tag = substr($tagAndValue[0], 1);
		if (count($tagAndValue) > 1) {
			$this->tags[$tag][] = trim($tagAndValue[1]);
		} else {
			$this->tags[$tag] = array();
		}
	}
}

?>