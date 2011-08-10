<?php
	
	/**
	 * This file is part of Phoebe - Front-End Build Engine
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * files distributed in the lib folders.
	 */

	 /**
	 * Represents a single template, its source and output code
	 * variables and more.
	 */
	class Phoebe_Template {

		private $name = '';
		private $filepath = '';
		private $filename = '';
		private $title = '';
		private $description = '';
		private $code = '';
		private $aVars;

		function __construct($filepath, $title = '', $description = '') {
			$this->filepath = substr($filepath, 0, strrpos($filepath, "/")+1);
			$this->filename = substr($filepath, strrpos($filepath, "/")+1);
			$this->title = $title;
			$this->description = $description;

			// set up local variables
			$this->aVars = array();
			$this->aVars['templateTitle'] = $this->getTitle();
			$this->aVars['templateDescription'] = $this->getDescription();
		}

		public function getName() {
			return $this->name;
		}

		public function setName($name) {
			$this->name = $name;
		}

		public function getFilename() {
			return $this->filename;
		}

		public function getTitle() {
			return $this->title;
		}

		public function setTitle($title) {
			$this->title = $title;
		}		

		public function getDescription() {
			return $this->description;
		}

		public function setDescription($description) {
			$this->description = $description;
		}

		public function setVariable($key, $value) {
			$this->aVars[$key] = $value;
		}

		public function getVariables() {
			return $this->aVars;
		}

		public function getCode() {
			if ($this->code == '') {
				$this->load();
			}
			
			return $this->code;
		}

		public function getOutputFilename() {
			return $this->filename;
		}

		private function load() {
			if (file_exists($this->filepath . "/" . $this->filename)) {
				$this->code = file_get_contents($this->filepath . "/" . $this->filename);
			} else {
				print(PHP_EOT . 'Error: Could not find file "' . $this->filepath . '/' . $this->filename . '"\'');
			}
		}
	}

?>