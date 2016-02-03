<?php
/**
 * 删除煎蛋网小图片
 */
$path = '/Users/haven/Pictures/网上down/煎蛋网 妹子图';
getfiles($path);
$count = 0;

function getfiles($path) {
	global $count;
	if (!is_dir($path)) {return;
	}

	$handle = opendir($path);
	while (false !== ($file = readdir($handle))) {
		if ($file != '.' && $file != '..') {
			$path2 = $path.'/'.$file;
			if (is_dir($path2)) {
				//echo '';
				//echo $file;
				getfiles($path2);
			} else {
				if (false !== strpos($file, ".jpg") || false !== strpos($file, ".gif")) {
					if (filesize($path2) < 50*1024) {
						unlink($path2);
						$count++;
						echo $count."\n";
					}
				}
				//echo '';
				//echo $file;
			}
		}
	}
}
