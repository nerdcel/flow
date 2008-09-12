<?php
declare(ENCODING = 'utf-8');
namespace F3::FLOW3::MVC::Web;

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
 * @subpackage MVC
 * @version $Id:F3::FLOW3::MVC::Web::Request.php 467 2008-02-06 19:34:56Z robert $
 */

/**
 * Represents a web request.
 *
 * @package FLOW3
 * @subpackage MVC
 * @version $Id:F3::FLOW3::MVC::Web::Request.php 467 2008-02-06 19:34:56Z robert $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 *
 * @scope prototype
 */
class Request extends F3::FLOW3::MVC::Request {

	/**
	 * @var string The requested representation format
	 */
	protected $format = 'html';

	/**
	 * @var string Contains the request method
	 */
	protected $method = F3::FLOW3::Utility::Environment::REQUEST_METHOD_GET;

	/**
	 * @var F3::FLOW3::Utility::Environment
	 */
	protected $environment;

	/**
	 * @var F3::FLOW3::Property::DataType::URI The request URI
	 */
	protected $requestURI;

	/**
	 * @var F3::FLOW3::Property::DataType::URI The base URI for this request - ie. the host and path leading to the index.php
	 */
	protected $baseURI;

	/**
	 * Injects the environment
	 *
	 * @param F3::FLOW3::Utility::Environment $environment
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function injectEnvironment(F3::FLOW3::Utility::Environment $environment) {
		$this->environment = $environment;
	}

	/**
	 * Sets the request method
	 *
	 * @param string $method Name of the request method - one of the F3::FLOW3::Utility::Environment::REQUEST_METHOD_* constants
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 * @throws F3::FLOW3::MVC::Exception::InvalidRequestMethod if the request method is not supported
	 */
	public function setMethod($method) {
		if (array_search($method, array(
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_GET,
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_POST,
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_DELETE,
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_PUT,
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_HEAD,
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_OPTIONS,
				F3::FLOW3::Utility::Environment::REQUEST_METHOD_UNKNOWN
			)) === FALSE) throw new F3::FLOW3::MVC::Exception::InvalidRequestMethod('The request method "' . $method . '" is not supported.', 1217778382);
		$this->method = $method;
	}

	/**
	 * Returns the name of the request method
	 *
	 * @return string Name of the request method - one of the F3::FLOW3::Utility::Environment::REQUEST_METHOD_* constants
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Sets the request URI
	 *
	 * @param F3::FLOW3::Property::DataType::URI $requestURI URI of this web request
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setRequestURI(F3::FLOW3::Property::DataType::URI $requestURI) {
		$this->requestURI = clone $requestURI;
		$this->baseURI = $this->detectBaseURI($requestURI);
	}

	/**
	 * Returns the request URI
	 *
	 * @return F3::FLOW3::Property::DataType::URI URI of this web request
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getRequestURI() {
		return $this->requestURI;
	}

	/**
	 * Sets the base URI for this request.
	 *
	 * @param F3::FLOW3::Property::DataType::URI $baseURI New base URI
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setBaseURI(F3::FLOW3::Property::DataType::URI $baseURI) {
		$this->baseURI = clone $baseURI;
	}

	/**
	 * Returns the base URI
	 *
	 * @return F3::FLOW3::Property::DataType::URI Base URI of this web request
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getBaseURI() {
		return $this->baseURI;
	}

	/**
	 * Tries to detect the base URI of this request and returns it.
	 *
	 * @param F3::FLOW3::Property::DataType::URI $requestURI URI of this web request
	 * @return F3::FLOW3::Property::DataType::URI The detected base URI
	 * @author Robert Lemke <robert@typo3.org>
	 */
	protected function detectBaseURI(F3::FLOW3::Property::DataType::URI $requestURI) {
		$baseURI = clone $requestURI;
		$baseURI->setQuery(NULL);
		$baseURI->setFragment(NULL);

		$requestPathSegments = explode('/', $this->environment->getScriptRequestPathAndName());
		array_pop($requestPathSegments);
		$baseURI->setPath(implode('/', $requestPathSegments) . '/');
		return $baseURI;
	}
}
?>