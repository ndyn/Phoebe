<?php

	/**
	 * This file is part of Phoebe - Front-End Build Engine
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * files distributed in the lib folders.
	 */

	 /**
	 * This script uses Phoebe to render frontend templates, partials 
	 * and variables into a static export for publishing. Phoebe will create a
	 * destination folder, copy static and render dynamic objects into it and also
	 * generate dummy images as placeholders.
	 * 
	 * When publishing to production, we recommend running the HTML5 Boilerplate
	 * build scripts right after running Phoebe.
	 */

	// configuration
	$sourceFolder = getcwd() . '/' . 'source';
	$destinationFolder = getcwd() . '/' . 'export';
	
	// load templating engine
	require_once(getcwd() . '/lib/Phoebe/Autoloader.php');
	Phoebe_Autoloader::register();

	// load Phoebe
	$phoebe = new Phoebe_Engine($sourceFolder, $destinationFolder);

	// add templates
	$phoebe->registerTemplate('template-1-0.html', 'Template 1.0', 'Homepage');
	$phoebe->registerTemplate('template-2-0.html', 'Template 2.0', 'Default Article');
	$phoebe->registerTemplate('template-3-0.html', 'Template 3.0', 'Article with special JavaScript feature');

	// add global variables
	$phoebe->registerVariable('projectName', 'Demo Project');

	// render out source folder and registered templates
	$phoebe->renderFolder($sourceFolder, $destinationFolder);

	// generate required dummy images
	$phoebe->generateDummyImage(1024, 250);
	$phoebe->generateDummyImage(200, 300);
?>