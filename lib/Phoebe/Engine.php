<?php

	/**
	 * This file is part of Phoebe - Front-End Build Engine
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * files distributed in the lib folders.
	 */

	 /**
	 * Main class of this package
	 */
	class Phoebe_Engine {

		// default values
		private $sourceFolder = 'source';
		private $destinationFolder = 'export';
		private $aTemplates;
		private $aGlobalVars;

		// templating engine
		private $twig;

		// phoebe's own helpers
		private $imageGenerator;

		function __construct($sourceFolder, $destinationFolder) {

			$this->sourceFolder = $sourceFolder;
			$this->destinationFolder = $destinationFolder;
			$this->aTemplates = array();
			$this->aGlobalVars = array();

			// load templating engine
			require_once(getcwd() . '/lib/Twig/Autoloader.php');
			Twig_Autoloader::register();

			$loader = new Twig_Loader_Filesystem($sourceFolder);
			$this->twig = new Twig_Environment($loader, array());

			// load image generator
			$this->imageGenerator = new Phoebe_ImageGenerator();
		} 

		public static function createEmptyFolder ($folder) {

			if (is_dir($folder)) {
				print(PHP_EOL . 'Removing existing folder "' . $folder . '"...');
				self::rrmdir($folder);
				print('done.');
			}		

			if (!is_dir($folder)) {
				print(PHP_EOL . 'Generating target folder  "' . $folder . '"....');
				mkdir($folder);
				print('done.');
			}
		}

		public function renderFolder($sourceFolder, $destinationFolder) {
			
			print(PHP_EOL . "Phoebe - Frontend Build Engine" . PHP_EOL);

			self::createEmptyFolder($destinationFolder);

			print(PHP_EOL . "Copying public resources...");
			self::exportFiles($sourceFolder . '/public', $destinationFolder);
			print('done.');

			foreach($this->aTemplates as $template) {
				print(PHP_EOL . 'Rendering file: "' . $template->getFilename() . '"...');
				$this->exportTemplate($template, $destinationFolder);
				print('done.');
			}

			print(PHP_EOL . 'Done.' . PHP_EOL . PHP_EOL);
		}

		// copies all files and folders in a folder to the export directory
		private static function exportFiles($source, $destination, $generateFolders=false) {

			if (is_dir($source) && $dirHandle = opendir($source)) {
				if ($generateFolders) {
					mkdir($destination . '/' . $entry);
				}
				
				while (false !== ($entry = readdir($dirHandle))) {
					if (substr($entry, 0, 1) != ".") {
						self::exportFiles($source . '/' . $entry, $destination . '/' . $entry, true);
					}
				}
				closedir($dirHandle);
			}
			else {
				$fileHandle = fopen($destination, "w") or die(PHP_EOL . 'Error: Cannot write file ' . $destination);
				fwrite($fileHandle, file_get_contents($source));
				fclose($fileHandle);
			}
		}

		// register templates
		public function registerTemplate($filename, $title = '', $description = '') {

			if (!file_exists($filename)) {
				$filename = $this->sourceFolder . '/' . $filename;
			}

			$template = new Phoebe_Template($filename, $title, $description);
			$this->aTemplates[] = $template;

		}

		// register global variable
		public function registerVariable($name, $value) {
			
			$this->aGlobalVars[$name] = $value;

		}

		// return all .php files in a folder as templates
		private static function getTemplatesFromFolder($sourceFolder) {
			
			$templates = array();
			
			if ($dirhandler = opendir($sourceFolder)) {
				while (false !== ($file = readdir($dirhandler))) {
					if (substr($file, -5) == ".html") {
						$templates[] = new Phoebe_Template($sourceFolder . "/" . $file);
					}
			    	}
				closedir($dirhandler);
			}

			return $templates;
		}

		// writes a file into the filesystem
		private function exportTemplate($template, $destinationFolder) {
			
			$fileHandle = fopen($destinationFolder . "/" . $template->getOutputFilename(), "w") or die(PHP_EOL . 'Error: Cannot write file ' . $destinationFolder . "/" . $template);
			print('exporting file...');
			fwrite($fileHandle, $this->renderTemplate($template));
			fclose($fileHandle);
		}

		// renders one template
		private function renderTemplate($template) {
			
			$twig_template = $this->twig->loadTemplate($template->getFilename());
			$aVariables = array_merge($this->aGlobalVars, $template->getVariables());

			return $twig_template->render($aVariables);
		}

		// recursively remove non-empty folders
		 public static function rrmdir($dir) {
			if (is_dir($dir)) {
				$objects = scandir($dir);
				foreach ($objects as $object) {
					if ($object != "." && $object != "..") {
						if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object);
					}
				}
				reset($objects);
				rmdir($dir);
			}
		} 

		// generate dummy images
		public function generateDummyImage($width, $height, $filename = '') {

			if ($filename == '') {
				$filename = $this->destinationFolder . '/img/dummy-' . $width . 'x' . $height . '.png';
			}

			$this->imageGenerator->generateDummyImage($width, $height, $filename);
		}
	}

?>