<?php

namespace App\Controllers\Helpers;

use App\Data\Constants;
use App\Data\Models\File;
use Illuminate\Support\Str;
use Storage;

class FileUploadHelper
{

	const VALID_FILE_NAME_PREFIXES = 'DATA,DEST,settlement';

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
	public static function getFileTypeDataPerFileName($fileName)
	{

		$prefix = '';
		$fileTypeData = null;

		$validFileNamePrefixes = explode(',', self::VALID_FILE_NAME_PREFIXES);

		foreach ($validFileNamePrefixes as $key => $validPrefix) {
			if (Str::startsWith($fileName, $validPrefix)) {
				$prefix = $validPrefix;
				break;
			}
		}

		if ($prefix != '') {

			switch ($prefix) {
				case 'DATA':
					$type    = 'Certificates of Data Wipe';
					$typeDir = '/certificate_of_data_wipe';
					break;

				case 'DEST':
					$type    = 'Certificates of Recycling';
					$typeDir = '/certificate_of_destruction';
					break;

				case 'settlement':
					$type    = 'Settlements';
					$typeDir = '/settlement';
					break;
			}

			$fileTypeData = array(
				'prefix'  => $prefix,
				'type'    => $type,
				'typeDir' => $typeDir
			);
		}

		return $fileTypeData;
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
