<?php
/**
 * @copyright Copyright (C) 2006,2007,2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 *
 * Helper class for URL handling.  Parses URLs and allows adding parameters from variables.
 *
 * $url = new URL('/path/to/webpage.php?initialParameter=whatever');
 * $url->parameters['somevar'] = $somevar;
 * $url->somevar = $somevar;
 * echo $url->getURL();
 */
class URL
{
	private $protocol;
	private $script;
	public $parameters = array();

	public function __construct($script="",$parameters=array())
	{
		$this->parameters = $parameters;
		$this->setScript($script);
	}

	public function setScript($script)
	{
		$script = urldecode($script);

		if (preg_match('|://|',$script))
		{
			list($protocol,$script) = explode('://',$script);
			$this->protocol = "$protocol://";
		}
		else
		{
			$this->protocol = $_SERVER['SERVER_PORT']==443 ? 'https://' : 'http://';
		}

		# Parse any parameters already in the script
		if (ereg("\?",$script))
		{
			list($script,$parameters) = explode("?",$script);

			$parameters = preg_split('/[;&]/',$parameters);
			foreach($parameters as $parameter)
			{
				if (ereg("=",$parameter))
				{
					list($field,$value) = explode("=",$parameter);
					if ($value) { $this->parameters[$field] = $value; }
				}
			}
		}

		$this->script = $script;
	}
	public function getScript() { return $this->script; }

	public function getURL()
	{
		$url = $this->protocol.$this->script;
		if (count($this->parameters))
		{
			$url.= "?";
			foreach($this->parameters as $key=>$value) { $url.= urlencode($key).'='.urlencode($value).';'; }
		}
		return $url;
	}

	public function getProtocol()
	{
		if (!$this->protocol) { $this->protocol = 'http://'; }
		return $this->protocol;
	}
	public function setProtocol($string)
	{
		if (!preg_match('|://|',$string)) { $string .= '://'; }
		$this->protocol = $string;
	}

	public function __toString() { return $this->getURL(); }

	/**
	 * Overloading methods
	 * These will let us get and set parameters directly
	 */
	public function __set($string,$value) { $this->parameters[$string] = $value; }
	public function __isset($string) { return isset($this->parameters[$string]); }
	public function __unset($string) { 	unset($this->parameters[$string]); }
	public function __get($string)
	{
		if (isset($this->parameters[$string])) { return $this->parameters[$string]; }
	}
}
