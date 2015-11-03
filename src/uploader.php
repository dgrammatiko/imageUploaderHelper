<?php
/**
 * @package     Uploader plugin
 *
 * @copyright   Copyright (C) 2015 Dimitris Grammatikogiannis. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Uploader plugin
 *
 * By: Dimitris Grammatikogiannis
 *
 * http://www.upshift.gr
 *
 */
class PlgContentUploader extends JPlugin
{
	/**
	 * Plugin that manipulate uploaded images
	 *
	 * @param   string   $context       The context of the content being passed to the plugin.
	 * @param   object   &$object_file  The file object.
	 *
	 * @return  object  The file object.
	 */
	public function onContentBeforeSave($context,  &$object_file)
	{
		// Are we in the right context?
		if ($context != 'com_media.file')
		{
			return;
		}

		// Get the current date
		$date  = JDate::getInstance('now');

		// Respect the timezone
		$config = JFactory::getConfig();
		$date->setTimezone(new DateTimeZone($config->get('offset')));

		// Set current year, month, day
		$year  = $date->year;
		$month = $date->month;
		$day   = $date->day;

		// Set the images subfolder, defaults to images/uploads
		$folder   = $this->params->get('folder');
		$folder   = (isset($folder)) ? $folder . '/' : '';
		$basePath = JPATH_ROOT . '/images/' . $folder;

		// Make some directories checks
		if (!is_dir(rtrim($basePath, "/")))
		{
			mkdir(rtrim($basePath, "/"));
		}

		if (!is_dir($basePath . $year))
		{
			mkdir($basePath . $year);
		}

		if (!is_dir($basePath . $year . '/' . $month))
		{
			mkdir($basePath . $year . '/' . $month);
		}

		if (!is_dir($basePath . $year . '/' . $month . '/' . $day))
		{
			mkdir($basePath . $year . '/' . $month . '/' . $day);
		}

		// Update the object to the new path
		$object_file->filepath = $basePath . $year . '/' . $month . '/' . $day . '/' . $object_file->name;

		return $object_file;
	}

	/**
	 * Plugin that manipulate uploaded images
	 *
	 * @param   string   $context       The context of the content being passed to the plugin.
	 * @param   object   &$object_file  The file object.
	 *
	 * @return  object  The file object.
	 */
	public function onContentAfterSave($context,  &$object_file)
	{
		// Are we in the right context?
		if ($context != 'com_media.file')
		{
			return;
		}

		$file = pathinfo($object_file->filepath);

		// Skip if the pass through keyword is set
		if (preg_match('/'. $this->params->get('passthrough') . '_/', $file['filename']))
		{
			return;
		}

		$image = new JImage;

		// Load the file
		$image->loadFile($object_file->filepath);

		// Get the properties
		$properties = $image->getImageFileProperties($object_file->filepath);

		// Skip if the width is less or equal to the required
		if ($properties->width <= $this->params->get('maxwidth'))
		{
			return;
		}

		// Get the image type
		if (preg_match('/jp(e)g/', mb_strtolower($properties->mime)))
		{
			$imageType = 'IMAGETYPE_JPEG';
		}

		if (preg_match('/gif/', mb_strtolower($properties->mime)))
		{
			$imageType = 'IMAGETYPE_GIF';
		}

		if (preg_match('/png/', mb_strtolower($properties->mime)))
		{
			$imageType = 'IMAGETYPE_PNG';
		}

		// Resize the image
		$image->resize($this->params->get('maxwidth'), '', false);

		// Overwrite the file
		$image->toFile($object_file->filepath, $imageType, array('quality' => $this->params->get('quality')));

		return $object_file;
	}
}
