<?php

namespace App\Controllers\Helpers;

use App\Data\Constants;
use App\Data\Models\File;
use Storage;

class FileUploadHelper
{

	public function __construct() {
	}

	/**
	 * @param string $type
	 * @return string
	 */
	public static function getFilePageTypeDir($type)
	{
		$pageTypeDir = '';
		switch ($type) {
			case 'Certificates of Data Wipe':
				$pageTypeDir = '/certificate_of_data_wipe';
				break;

			case 'Certificates of Recycling':
				$pageTypeDir = '/certificate_of_destruction';
				break;

			case 'Settlements':
				$pageTypeDir = '/settlement';
				break;
		}
		return $pageTypeDir;
	}

	/**
	 * @param string $type
	 * @return string
	 */
	public static function getFilePrefixPerType($type)
	{
		$fileNamePrefix = '';
		switch ($type) {
			case 'Certificates of Data Wipe':
				$fileNamePrefix = 'DATA';
				break;

			case 'Certificates of Recycling':
				$fileNamePrefix = 'DEST';
				break;

			case 'Settlements':
				$fileNamePrefix = 'settlement';
				break;
		}
		return $fileNamePrefix;
	}

	/**
	 * @param string $prefix
	 * @return string
	 */
	public static function getFileTypePerPrefix($prefix)
	{
		$fileType= '';
		switch ($prefix) {
			case 'DATA':
				$fileType = 'Certificates of Data Wipe';
				break;

			case 'DEST':
				$fileType = 'Certificates of Recycling';
				break;

			case 'settlement':
				$fileType = 'Settlements';
				break;
		}
		return $fileType;
	}

	public static function removeFile(File $file)
	{
		$pageTypeDir = self::getFilePageTypeDir($file->page->type);
		if ($pageTypeDir != '') {
			if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $file->page->site->code . $pageTypeDir . '/' . $file->filename)) {
				Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $file->page->site->code . $pageTypeDir . '/' . $file->filename);
			}
		}
		$file->delete();
	}
}
