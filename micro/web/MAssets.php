<?php

class MAssets
{
	private $assetDir = 'assets';

	public function publish($dir) {
		$hashDir = getPublishDir($dir);

		if (is_dir($dir)) {
			MFile::recurseCopyIfEdited($dir, $hashDir);
		} else {
			if (filemtime($dir) != filemtime($hashDir)) {
				copy($dir, $hashDir);
				@chmod($dstDir, 0666);
			}
		}
	}

	public function getPublishDir($dir) {
		return $this->assetDir . DIRECTORY_SEPARATOR . hash($dir);
	}
}