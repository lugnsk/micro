<?php

class MFile
{
	public static function recurseCopy($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst, 0777);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
					@chmod($dst . '/' . $file, 0666);
				}
			}
		}
		closedir($dir);
	}

	public static function recurseCopyIfEdited($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst, 0777);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					if (filemtime($src . '/' . $file) != filemtime($dst . '/' . $file)) {
						copy($src . '/' . $file,$dst . '/' . $file);
						@chmod($dst . '/' . $file, 0666);
					}
				}
			}
		}
		closedir($dir);
	}
}