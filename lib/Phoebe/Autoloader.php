<?php
    
	/**
	 * This file is part of Phoebe - Front-End Build Engine
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * files distributed in the lib folders.
	 */

	 /**
	 * Makes PHP classes available as they are requested.
	 * This code is strongly inspired by Fabien Potencier and Twig
	 */
	class Phoebe_Autoloader
	{
		static public function register()
		{
			ini_set('unserialize_callback_func', 'spl_autoload_call');
			spl_autoload_register(array(new self, 'autoload'));
		}

		static public function autoload($class)
		{
			if (0 !== strpos($class, 'Phoebe')) {
				return;
			}

			if (file_exists($file = dirname(__FILE__).'/../'.str_replace(array('_', "\0"), array('/', ''), $class).'.php')) {
				require $file;
			}
		}
	}

?>