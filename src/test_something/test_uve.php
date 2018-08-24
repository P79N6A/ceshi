<?php
$result = '<?xml version="1.0" encoding="utf-8"?>

<openheader enterweibo="0" allowinteraction="1"> 
  <imgs_ios num="2"> 
    <img id="open1" width="750" height="1334" src="http://aev2test.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201807/04/1468781.png" link=""/>  <img id="open2" width="750" height="1624" src="http://aev2test.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201807/04/1468782.png" link=""/> 
  </imgs_ios>  
  <allowinteraction_ios num="16"> 
    <img id="RectNormalOpen1" click_rect_top1="71.66" click_rect_bottom1="81.11" btn_rect_left1="0" btn_rect_right1="100" btn_image_width1="750" btn_image_height1="126" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/26/1468486.png" click_url1=""/>  
    <img id="RectNormalOpen2" click_rect_top2="91" click_rect_bottom2="97" btn_rect_left2="0" btn_rect_right2="100" btn_image_width2="750" btn_image_height2="76" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/12/1468379.png" click_url2="wbad://closead"/> 
	<img id="RectNormalOpen3" click_rect_top3="73.8" click_rect_bottom3="81.5" btn_rect_left3="0" btn_rect_right3="100" btn_image_width3="750" btn_image_height3="126" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/26/1468486.png" click_url3=""/>  
    <img id="RectNormalOpen4" click_rect_top4="89.5" click_rect_bottom4="94.2" btn_rect_left4="0" btn_rect_right4="100" btn_image_width4="750" btn_image_height4="76" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/12/1468379.png" click_url4="wbad://closead"/> 
  </allowinteraction_ios>  <imgs_android num="4"> 
    <img id="open1" width="720" height="1184" src="http://aev2test.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201807/04/1468783.png" link=""/>  <img id="open2" width="720" height="1280" src="http://aev2test.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201807/04/1468784.png" link=""/> <img id="open3" width="720" height="1356" src="http://aev2test.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201807/04/1468785.png" link=""/>  <img id="open4" width="720" height="1480" src="http://aev2test.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201807/04/1468786.png" link=""/> 
  </imgs_android>  
  <allowinteraction_android num="8"> 
    <img id="RectNormalOpen1" click_rect_top1="68" click_rect_bottom1="81"  btn_rect_left1="0" btn_rect_right1="100" btn_image_width1="720" btn_image_height1="180" src="http://u1.img.mobile.sina.cn/public/files/image/720x180_img56404f1646bf0.png" click_url1=""/>  
    <img id="RectNormalOpen2" click_rect_top2="91" click_rect_bottom2="97" btn_rect_left2="0" btn_rect_right2="100" btn_image_width2="720" btn_image_height2="72" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/12/1468381.png" click_url2="wbad://closead"/> 
	<img id="RectNormalOpen3" click_rect_top3="68" click_rect_bottom3="81" btn_rect_left3="0" btn_rect_right3="100" btn_image_width3="720" btn_image_height3="180" src="http://u1.img.mobile.sina.cn/public/files/image/720x180_img56404f1646bf0.png" click_url3=""/>  
    <img id="RectNormalOpen4" click_rect_top4="91" click_rect_bottom4="97" btn_rect_left4="0" btn_rect_right4="100" btn_image_width4="720" btn_image_height4="72" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/12/1468381.png" click_url4="wbad://closead"/> 
	
	<img id="RectNormalOpen5" click_rect_top5="73.3" click_rect_bottom5="82.2" btn_rect_left5="0" btn_rect_right5="100" btn_image_width5="720" btn_image_height5="120" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/25/1468466.png" click_url5=""/>  
    <img id="RectNormalOpen6" click_rect_top6="91.3" click_rect_bottom6="96.6" btn_rect_left6="0" btn_rect_right6="100" btn_image_width6="720" btn_image_height6="72" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/12/1468381.png" click_url6="wbad://closead"/> 

	<img id="RectNormalOpen7" click_rect_top7="75.5" click_rect_bottom7="83.6" btn_rect_left7="0" btn_rect_right7="100" btn_image_width7="720" btn_image_height7="120" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/25/1468466.png" click_url7=""/>  
    <img id="RectNormalOpen8" click_rect_top8="92.0" click_rect_bottom8="96.9" btn_rect_left8="0" btn_rect_right8="100" btn_image_width8="720" btn_image_height8="72" src="http://aev2.erp.sina.com.cn/index.php/b/file-show-display?filepath=upload/201712/12/1468381.png" click_url8="wbad://closead"/> 
  </allowinteraction_android> 
</openheader>';

echo '<pre>';
$data = @simplexml_load_string(trim($result), 'SimpleXMLElement', LIBXML_SCHEMA_CREATE);
$data = json_decode(json_encode($data), true);
var_export($data);

