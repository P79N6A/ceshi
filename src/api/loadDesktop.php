<?php

//-------------------------
// 程序通用函数
//-------------------------
set_time_limit(0);
require 'lib/mysql.class.php';
require 'lib/Snoopy.class.php';
require 'lib/simplehtmldom_1_5/simple_html_dom.php';

/**
 * 随机获得网址
 */
function randUrl()
{
    $urlData = array('http://www.sohu.com/', 'http://www.sogou.com/', 'http://pinyin.sogou.com/', 'http://ie.sogou.com/', 'http://map.sogou.com/', 'http://mail.sohu.com/', 'http://t.sohu.com/', 'http://blog.sohu.com/', 'http://club.sohu.com/', 'http://pinglun.sohu.com/', 'http://mini.sohu.com/', 'http://pic.sohu.com/', 'http://tv.sohu.com/', 'http://class.chinaren.com/', 'http://www.17173.com/', 'http://xtl.changyou.com/', 'http://eos.changyou.com/', 'http://www.m.sohu.com/', 'http://mgame.sohu.com/', 'http://egou.focus.cn', 'http://news.sohu.com/', 'http://mil.sohu.com/', 'http://cul.sohu.com/', 'http://history.sohu.com/', 'http://book.sohu.com/', 'http://app.sohu.com/', 'http://star.news.sohu.com/', 'http://sports.sohu.com/', 'http://sports.sohu.com/nba.shtml', 'http://cbachina.sports.sohu.com/', 'http://sports.sohu.com/zhongchao.shtml', 'http://golf.sports.sohu.com/', 'http://business.sohu.com/', 'http://money.sohu.com/', 'http://stock.sohu.com/', 'http://fund.sohu.com/', 'http://it.sohu.com/', 'http://digi.it.sohu.com/', 'http://digi.it.sohu.com/mobile.shtml', 'http://auto.sohu.com/', 'http://2sc.sohu.com/', 'http://caipiao.sohu.com/', 'http://fashion.sohu.com/', 'http://women.sohu.com/', 'http://beauty.sohu.com/', 'http://chihe.sohu.com/', 'http://astro.sohu.com/', 'http://baobao.sohu.com/', 'http://health.sohu.com/', 'http://travel.sohu.com/', 'http://learning.sohu.com/', 'http://goabroad.sohu.com/', 'http://learning.sohu.com/gaokao.shtml', 'http://gongyi.sohu.com/', 'http://my.tv.sohu.com/', 'http://yule.sohu.com/', 'http://tv.sohu.com/drama/us/', 'http://music.sohu.com/', 'http://www.focus.cn', 'http://esf.focus.cn/search/', 'http://home.focus.cn', 'http://city.sohu.com/', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=9b1dac39,c29c50d9,d8c3cf70,25fded75&AdID=16672628', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=4848ab17,9e497bd8,b69f95d0,d487229b&AdID=8887450', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=e891801d,74e9c4ad,bc752e10,8f732491&AdID=7577232', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=d6fe8936,5ed831b6,d55f0733,df2e97bf&AdID=3084734', 'http://vip.book.sohu.com/book/144664/', 'http://vip.book.sohu.com/book/149700/', 'http://vip.book.sohu.com/book/101459/', 'http://learning.sohu.com/', 'http://goabroad.sohu.com/', 'http://learning.sohu.com/s2014/library/', 'http://learning.sohu.com/20140521/n399830067.shtml', 'http://bschool.sohu.com/', 'http://learning.sohu.com/s2014/xbqzf/?pvid=6aa0c692c0b968f8', 'http://goabroad.sohu.com/s2014/australia/', 'http://goabroad.sohu.com/s2014/immigrationsalon/', 'http://goabroad.sohu.com/s2013/immigration/', 'http://goabroad.sohu.com/s2013/lowage/', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=69ae4095,b16ae16e,34b22218,29126148&AdID=1235783', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=700b1598,14713e43,37150a30,8392672e&AdID=9625901', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=3d96ea3c,293eca17,db2a374b,0eae0e16&AdID=11438101', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=b71b2fa8,7bb84e5d,477c4550,02d3f64f&AdID=13922382', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=d1d9d5ac,5ad30d03,4b5be312,e7c6772e&AdID=13037869', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=b4d2edbf,81b6061b,5e824745,ad62772b&AdID=6484266', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201312&TargetID=sohu&Values=1183de2f,2812b70b,ce297ddc,7c545fb8&AdID=5560759', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201409&TargetID=sohu&Values=ecbaef65,ededee42,e7eea54f,f868147b&AdID=6861917', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=c5e1ccf2,dbc71520,c7dc3019,84704ec2&AdID=7401207', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=de982b9c,b0dfcb7f,ceb11fa9,9afdf7ec&AdID=16685182', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201402&TargetID=sohu&Values=40441f53,93417770,0e942a83,6c29c01d&AdID=2776376', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=85a49f2e,6f86d7f3,1170cb07,36952987&AdID=9816522', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=d01e58bb,56d151ac,8b5770b3,97cf903a&AdID=13643882', 'http://vip.book.sohu.com/book/132738/', 'http://tj.focus.cn/votehouse/lpdt/71488/', 'http://tj.focus.cn/votehouse/lpdt/71487/', 'http://tj.focus.cn/votehouse/lpdt/71479/', 'http://tj.focus.cn/votehouse/lpdt/71432/', 'http://tj.focus.cn/votehouse/lpdt/71434/', 'http://tj.focus.cn/zhuanti14/jdkj/', 'http://tj.focus.cn/zhuanti14/tianjinqiujifangjiaohui/', 'http://tj.focus.cn/zhuanti14/jddt24/', 'http://tj.focus.cn/zhuanti13/zrhzmq/', 'http://tj.focus.cn/newscenter/newscenter.php?prep_subj_id=71', 'http://zhuanti.focus.cn/2014/house_qhdfj/', 'http://qhd.focus.cn/zhuanti14/house_lpgzd/', 'http://qhd.focus.cn/zhuanti14/qhd_hdfy4/', 'http://qhd.focus.cn/zhuanti14/qhd_hdfy5/', 'http://qhd.focus.cn/zhuanti14/jflp/', 'http://sjz.focus.cn/zhuanti14/vguanxinpanbaolilafeigongguan/', 'http://sjz.focus.cn/zhuanti14/luchengyihaoceping/', 'http://sjz.focus.cn/zhuanti14/zhongchuguangchangceping/', 'http://sjz.focus.cn/zhuanti14/shijiazhuangzhiyebaike17/', 'http://sjz.focus.cn/zhuanti14/loushiduiyi16/', 'http://ty.focus.cn/votehouse/lpdt/35399/', 'http://ty.focus.cn/votehouse/lpdt/35426/', 'http://ty.focus.cn/votehouse/lpdt/35366/', 'http://ty.focus.cn/votehouse/lpdt/35357/', 'http://ty.focus.cn/daogou/8277.html', 'http://ty.focus.cn/daogou/8324.html', 'http://ty.focus.cn/votehouse/lpdt/35284/', 'http://ty.focus.cn/votehouse/lpdt/35282/', 'http://ty.focus.cn/daogou/8498.html', 'http://ty.focus.cn/zhuanti14/ty400/', 'http://hhht.focus.cn/zhuanti14/house_fangjiaqingbaozhan24/', 'http://hhht.focus.cn/zhuanti14/genzheguihuaqumaifang02/', 'http://hhht.focus.cn/zhuanti14/house_huhehaotezuiredian15/', 'http://hhht.focus.cn/zhuanti14/hhht_kanfangtuanzhuanti11/', 'http://hhht.focus.cn/zhuanti14/house_miaoxingrenguangloushi3/', 'http://hhht.focus.cn/zhuanti14/hhhtzwangwenwenqie2/', 'http://hhht.focus.cn/zhuanti14/house_yizhouloushitianqibobao2/', 'http://hhht.focus.cn/zhuanti14/house_dazhezhuanti201411/', 'http://hhht.focus.cn/zhuanti14/house_2014loushiniandujiyiyingxiaopian/', 'http://hhht.focus.cn/zhuanti14/house_jdgz5/', 'http://sy.focus.cn/votehouse/lpdt/63446/', 'http://sy.focus.cn/votehouse/lpdt/63561/', 'http://sy.focus.cn/votehouse/lpdt/63751/', 'http://sy.focus.cn/votehouse/lpdt/63730/', 'http://sy.focus.cn/votehouse/lpdt/63742/', 'http://dl.focus.cn/votehouse/lpdt/45295/', 'http://dl.focus.cn/votehouse/lpdt/45286/', 'http://dl.focus.cn/votehouse/lpdt/44886/', 'http://dl.focus.cn/votehouse/lpdt/45306/', 'http://dl.focus.cn/votehouse/lpdt/45307/', 'http://cc.focus.cn/votehouse/993.html', 'http://cc.focus.cn/votehouse/256.html', 'http://cc.focus.cn/votehouse/982.html', 'http://cc.focus.cn/votehouse/1027.html', 'http://cc.focus.cn/votehouse/741.html', 'http://cc.focus.cn/votehouse/239.html', 'http://cc.focus.cn/votehouse/806.html', 'http://cc.focus.cn/votehouse/711.html', 'http://cc.focus.cn/votehouse/894.html', 'http://cc.focus.cn/votehouse/779.html', 'http://hrb.focus.cn/zhuanti14/house_hrb1410zhoumohaofangtuijian/', 'http://hrb.focus.cn/zhuanti14/house_hrb1410kaipan/', 'http://hrb.focus.cn/zhuanti14/house_hrb1410dazhe/', 'http://zhuanti.focus.cn/2014/tejiafang2014/', 'http://hrb.focus.cn/zhuanti14/hrb_qq/', 'http://hrb.focus.cn/daogou/7130.html', 'http://hrb.focus.cn/daogou/7116.html', 'http://hrb.focus.cn/daogou/7074.html', 'http://house.focus.cn/app/', 'http://hrb.focus.cn/housemarket/housemarket3.php?caculate=1', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=b12fcd03,90b26321,3691b1b4,82a6c7d5&AdID=10971240', 'http://www.sohu.com/ http://sh.focus.cn/votehouse/lpdt/68782/', 'http://www.sohu.com/ http://sh.focus.cn/votehouse/lpdt/68779/', 'http://www.sohu.com/ http://sh.focus.cn/votehouse/lpdt/68751/', 'http://www.sohu.com/ http://sh.focus.cn/votehouse/lpdt/68461/', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=4c5a4307,434d8d97,36446776,60df33df&AdID=14668910', 'http://www.sohu.com/ http://sh.focus.cn/votehouse/lpdt/68541/', 'http://www.sohu.com/ http://sh.focus.cn/votehouse/lpdt/68540/ ', 'http://sh.focus.cn/votehouse/lpdt/68370/ ', 'http://sh.focus.cn/votehouse/lpdt/68523/', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=1b174312,c41c4a96,f5c52d6e,91127674&AdID=1250999', 'http://nj.focus.cn/zhuanti14/jiangbeidaiyan/', 'http://nj.focus.cn/zhuanti14/huxiaolikanfang/', 'http://nj.focus.cn/zhuanti14/tianhuaban/', 'http://cz.focus.cn/daogou/8319.html', 'http://cz.focus.cn/votehouse/lpdt/29423/', 'http://suzhou.focus.cn/zhuanti14/house-4411kk88/', 'http://suzhou.focus.cn/votehouse/lpdt/36879/', 'http://wuxi.focus.cn/votehouse/lpdt/36460/', 'http://wuxi.focus.cn/votehouse/lpdt/36397/', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=7d95bf38,6d7ec913,6b6eb979,69c03cfb&AdID=12639630', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=627cf112,e063b044,f9e17b0a,bafc3adc&AdID=16571171', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=973a4779,a9986d9b,a9aa47be,034547b4&AdID=4581444', 'http://nb.focus.cn/votehouse/lpdt/31280/ ', 'http://hz.focus.cn/zhuanti14/house_11ykp/', 'http://nb.focus.cn/votehouse/lpdt/31234/', 'http://hz.focus.cn/zhuanti14/house_11ydz/', 'http://nb.focus.cn/daogou/5407.html', 'http://hz.focus.cn/zhuanti14/house_hangzhoufangjia10y/', 'http://kanfangtuan.focus.cn/hz/', 'http://hf.focus.cn/zhuanti14/house_hfyzfj9/', 'http://hf.focus.cn/daogou/8043.html', 'http://hf.focus.cn/daogou/8041.html', 'http://hf.focus.cn/daogou/8040.html', 'http://hf.focus.cn/daogou/8038.html', 'http://hf.focus.cn/zhuanti14/house_jinhuiyuefu/', 'http://hf.focus.cn/daogou/8042.html', 'http://hf.focus.cn/daogou/8006.html', 'http://hf.focus.cn/daogou/8002.html', 'http://hf.focus.cn/daogou/8004.html', 'http://www.sohu.com/ http://xm.focus.cn/zhuanti14/lsyzrd044/ ', 'http://www.sohu.com/ http://xm.focus.cn/zhuanti14/house_xmmykphz/ ', 'http://www.sohu.com/  http://xm.focus.cn/zhuanti14/yingxiaopian/ ', 'http://www.sohu.com/ http://xm.focus.cn/zhuanti14/zongzhuanti/ ', 'http://www.sohu.com/ http://xm.focus.cn/zhuanti14/wanrenpafan11/ ', 'http://fz.focus.cn/daogou/8320.html', 'http://fz.focus.cn/daogou/8383.html', 'http://fz.focus.cn/daogou/8527.html', 'http://fz.focus.cn/daogou/8568.html', 'http://jn.focus.cn/', 'http://jn.focus.cn/votehouse/lpdt/42382/', 'http://jn.focus.cn/daogou/8554.html', 'http://qd.focus.cn/news/2014-11-04/5713027.html', 'http://qd.focus.cn/zhuanti14/house_1411kp/', 'http://qd.focus.cn/msgview/6288/325330170.html', 'http://weihai.focus.cn/zhuanti14/weihai11yuedazhe/', 'http://weihai.focus.cn/news/2014-10-31/5704552.html', 'http://yt.focus.cn/msgview/460544/325351800.html', 'http://yt.focus.cn/votehouse/lpdt/9628/', 'http://zz.focus.cn/votehouse/lpdt/26603/', 'http://zz.focus.cn/votehouse/lpdt/26594/', 'http://zz.focus.cn/votehouse/lpdt/26546/', 'http://zz.focus.cn/daogou/8450.html', 'http://zz.focus.cn/daogou/8572.html', 'http://zz.focus.cn/daogou/8550.html', 'http://zz.focus.cn/daogou/8440.html', 'http://zz.focus.cn/daogou/8388.html', 'http://zz.focus.cn/daogou/8200.html', 'http://zz.focus.cn/daogou/8089.html', 'http://wh.focus.cn/zhuanti14/yzzls122/', 'http://wh.focus.cn/news/2014-11-03/5709634.html', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=a4943c74,85a5c790,5086a1dc,52d0f643&AdID=13735551', 'http://wh.focus.cn/zhuanti14/201411dzlp/', 'http://wh.focus.cn/zhuanti14/2014011mxlp/', 'http://wh.focus.cn/zhuanti14/whzybk07/', 'http://wh.focus.cn/zhuanti14/whzybk02/', 'http://wh.focus.cn/zhuanti14/yzfj1031/', 'http://wh.focus.cn/zhuanti14/whzybk04/', 'http://wh.focus.cn/zhuanti14/2014zzkft/', 'http://cs.focus.cn/zhuanti14/csgoufangdaxue/', 'http://cs.focus.cn/housemarket/zxlp.php?mon=11&year=2014', 'http://cs.focus.cn/zhuanti14/zfgjjnzl/', 'http://cs.focus.cn/zhuanti14/shjdwrdtg/', 'http://cs.focus.cn/zhuanti14/fcs/', 'http://cs.focus.cn/zhuanti14/cshushuoezhouloushidi10qi/', 'http://cs.focus.cn/daogou/8009.html', 'http://cs.focus.cn/daogou/7867.html', 'http://cs.focus.cn/votehouse/lpdt/39420/', 'http://cs.focus.cn/daogou/7735.html', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=57073f80,67583a93,b268a412,31809ed8&AdID=8470378', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=e2632d0b,84e39681,4185017b,0001142a&AdID=111808', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=15cf1a8e,9517026e,c096f47e,965131e8&AdID=5362298', 'http://info.focus.cn/news/2014-11-10/201342.html', 'http://zh.focus.cn/votehouse/lpdt/7193/', 'http://zh.focus.cn/votehouse/lpdt/7192/', 'http://dg.focus.cn/zhuanti14/house_dg201411kaipan/ ', 'http://dg.focus.cn/votehouse/lpdt/34981/', 'http://huizhou.focus.cn/votehouse/lpdt/22820/', 'http://fs.focus.cn/daogou/8504.html', 'http://nn.focus.cn/zhuanti14/house_hjds/', 'http://nn.focus.cn/zhuanti14/house_zlp3/', 'http://nn.focus.cn/zhuanti14/house_jingzhuangpan/', 'http://nn.focus.cn/daogou/8490.html', 'http://nn.focus.cn/daogou/8547.html', 'http://nn.focus.cn/zhuanti14/2014pdxy/', 'http://nn.focus.cn/zhuanti14/shiwenshiyue/', 'http://nn.focus.cn/news/2014-11-04/5717430.html', 'http://nn.focus.cn/news/2014-11-05/5722776.html', 'http://nn.focus.cn/news/2014-11-05/5722359.html', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=49b5fe16,124ae952,4c138c53,e9f48868&AdID=16066814', 'http://hn.focus.cn/daogou/7530.html', 'http://hn.focus.cn/daogou/8341.html', 'http://hn.focus.cn/daogou/8256.html', 'http://hn.focus.cn/daogou/8304.html', 'http://hn.focus.cn/daogou/4562.html', 'http://hn.focus.cn/daogou/6867.html', 'http://hn.focus.cn/daogou/8396.html', 'http://hn.focus.cn/daogou/8386.html', 'http://hn.focus.cn/daogou/8217.html', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201411&TargetID=sohu&Values=443cf615,2b45704a,482c9652,d6a7c4d5&AdID=11036008', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=21e4dad9,3c23182e,b23d1129,155df2cb&AdID=6198020', 'http://clk.optaim.com/event.ng/Type=click&FlightID=201410&TargetID=sohu&Values=c0b0bbcc,73c1e40f,a9747036,b1d4f6ad&AdID=13997802', 'http://kanfangtuan.focus.cn/chongqing/', 'http://cq.focus.cn/votehouse/lpdt/84610/', 'http://cq.focus.cn/zhuanti14/hzdn/', 'http://cq.focus.cn/zhuanti14/sdlwdcg/', 'http://cq.focus.cn/zhuanti14/xiangou/', 'http://cq.focus.cn/zhuanti14/souyidai/', 'http://cq.focus.cn/zhuanti14/house_201409dzyh/', 'http://cd.focus.cn/votehouse/lpdt/97987/', 'http://cd.focus.cn/news/2014-10-27/5683350.html', 'http://cd.focus.cn/daogou/8629.html', 'http://cd.focus.cn/daogou/8604.html', 'http://cd.focus.cn/votehouse/lpdt/97924/', 'http://cd.focus.cn/votehouse/lpdt/98015/', 'http://cd.focus.cn/votehouse/lpdt/98014/', 'http://cd.focus.cn/votehouse/lpdt/98083/', 'http://cd.focus.cn/votehouse/lpdt/97966/', 'http://cd.focus.cn/votehouse/lpdt/98153/');
    $index = rand(0, 300);
    return $urlData[$index];
}


/**
 * 随机获取ip
 */
function randIp()
{
    $a1 = rand(11, 250);
    $a2 = rand(11, 200);
    $a3 = rand(11, 150);
    $a4 = rand(11, 100);
    return "$a1.$a2.$a3.$a4";
}

/**
 * 结果输出
 * @param array $arr
 */
function pp($arr = '')
{
    if (is_array($arr)) {
        foreach ($arr as $val) {
            echo $val;
            echo "\t";
        }
    } else {
        echo $arr;
    }
    echo "\n";
}

/**
 * 随即设置抓取的snoopy地址ip，防止被封
 */
function randSnoopy()
{
    $GLOBALS['snoopy']->referer = randUrl();
    $GLOBALS['snoopy']->rawheaders["X_FORWARDED_FOR"] = randIp(); //伪装ip
}

/**
 * api结束
 */
function endApi()
{

    echo "\n\n-----------------------------------------\n";
    echo "|\tapi已执行结束\t\t\t|\n";
    echo "|\t\tMr.cui出品，必属精品\t|\n";
    echo "-----------------------------------------\n";

}

//-------------------------
// 程序开始了
//-------------------------
error_reporting(E_ERROR);
ini_set('display_errors', 1);

$GLOBALS['snoopy'] = new Snoopy;
$GLOBALS['snoopy']->proxy_port = "80";
$GLOBALS['snoopy']->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)";
$GLOBALS['snoopy']->rawheaders["Pragma"] = "no-cache"; //cache 的http头信息
$GLOBALS['snoopy']->read_timeout = 10;
$GLOBALS['mysql'] = new Mysql();
$GLOBALS['mysql']->setChar('utf8');

loadiDesktop();
endApi();


/**
 * 下载i壁纸
 */
function loadiDesktop()
{

    $filepath = '/Users/haven/Development/php/ceshi/iDesktop/';
    if (!is_dir($filepath)) {
        mkdir($filepath, 0777, true);
    }
    $err = 0;

    for ($i = 1374; $i >= 1314; $i--) {
        $nowpath = 'mm' . $i . '/';
        if (!is_dir($filepath . $nowpath)) {
            mkdir($filepath . $nowpath, 0777, true);
        }
        $href = 'http://jandan.net/ooxx/page-' . $i;
        randUrl();
        $GLOBALS['snoopy']->fetch($href);
        $html = str_get_html($GLOBALS['snoopy']->results);
        if (empty($html)) {
            echo 'no html';
            exit;
        }
        $divEl = $html->find('.commentlist', 0);
        if (empty($divEl)) {
            echo 'no commentlist';
            exit;
        }
        $lis = $divEl->childNodes();
        $c = count($lis);
        if ($c == 1) {
            continue;
        }
        foreach ($divEl->find('li') as $el) {

            foreach ($el->find('img') as $elimg) {
                $url = $elimg->src;
                $data = file_get_contents($url); // 读文件内容
                $ext = substr($url, -3, 3);
                if(empty($ext) || empty($data) || $ext == 'gif'){
                    continue;
                }
                $filename = uniqid() . '.' . $ext;
                $fp = @fopen($filepath . $nowpath . $filename, "w"); //以写方式打开文件
                @fwrite($fp, $data);
                fclose($fp);
                pp(array(++$err, $filename));
            }
        }
    }
}

//-------------------------
// 程序处理程序写在下面
//-------------------------
function loadStepsImg()
{
    $allDish = $GLOBALS['mysql']->getAll('select * from mr_goods');
    foreach ($allDish as $val) {
        if (empty($val['goods_steps'])) {
            continue;
        }
        $steps = unserialize($val['goods_steps']);
        $filepath = '/Users/cuihaicheng/Development/Apps/php/wordpress/static/goodsStepsThumb/';
        $deep = '2014' . sprintf("%04d", rand(1, 100)) . '/';
        $filepath .= $deep;
        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filepath .= $val['goods_id'] . '/';
        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        foreach ($steps as $k => &$v) {
            //下载本地图片
            $pic = $v[0];//远程文件路径
            $data = file_get_contents($pic); // 读文件内容
            $ext = substr($pic, -3, 3);
            $ext = empty($ext) ? 'jpg' : $ext;
            $filename = $k . '.' . $ext; //生成文件名，
            $dbName = 'goodsStepsThumb/' . $deep . $val['goods_id'] . '/' . $k . '.' . $ext; //生成文件名，
            $fp = @fopen($filepath . $filename, "w"); //以写方式打开文件
            @fwrite($fp, $data);
            fclose($fp);
            $v[2] = $dbName;
        }
        $steps = serialize($steps);
        $sql = "UPDATE `mr_goods` SET `goods_steps`='" . $steps . "' WHERE `goods_id`='" . $val['goods_id'] . "'";
        $r = $GLOBALS['mysql']->query($sql);
        if ($r) {
            pp(array($val['goods_id'], $val['goods_name']));
        } else {
            echo("fail\n");
        }
    }
}


function loadGoodsThumb()
{
    $allDish = $GLOBALS['mysql']->getAll('select goods_id,goods_img from mr_goods');
    foreach ($allDish as $val) {
        if (empty($val['goods_img'])) {
            continue;
        }
        //下载本地图片
        $pic = $val['goods_img'];//远程文件路径
        $data = file_get_contents($pic); // 读文件内容
        $filepath = '/Users/cuihaicheng/Development/Apps/php/wordpress/static/goodsthumb/';
        $dbName = 'goodsthumb/';
        $deep = '2014' . sprintf("%04d", rand(1, 100)) . '/';
        $filepath .= $deep;
        $dbName .= $deep;
        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $filename = $val['goods_id'] . '.' . substr($pic, -3, 3); //生成文件名，
        $dbName .= $val['goods_id'] . '.' . substr($pic, -3, 3); //生成文件名，
        $fp = @fopen($filepath . $filename, "w"); //以写方式打开文件
        @fwrite($fp, $data);
        fclose($fp);

        $sql = "UPDATE `mr_goods` SET `goods_thumb`='" . $dbName . "' WHERE `goods_id`='" . $val['goods_id'] . "'";
        $r = $GLOBALS['mysql']->query($sql);
        if ($r) {
            pp($val);
        } else {
            echo("fail\n");
        }
    }
}

function updateRelation()
{
//    $allDish = $GLOBALS['mysql']->getAll('select * from mr_goods_simple');
//    foreach ($allDish as $val) {
//        $sql = "UPDATE `mr_goods` SET `goods_href`='" . $val['dish_href'] . "' WHERE `goods_simple_id`='" . $val['id'] . "'";
//        $r = $GLOBALS['mysql']->query($sql);
//        if ($r) {
//            pp($val);
//        } else {
//            echo("fail\n");
//        }
//    }

    $allDish = $GLOBALS['mysql']->getAll('select goods_id,goods_simple_id from mr_goods');
    foreach ($allDish as $val) {
        $sql = "UPDATE `mr_goods_simple_relation` SET `goods_id`='" . $val['goods_id'] . "' WHERE `goods_id`='" . $val['goods_simple_id'] . "'";
        $r = $GLOBALS['mysql']->query($sql);
        if ($r) {
            pp($val);
        } else {
            echo("fail\n");
        }
    }
}

function fixNullName()
{

    $allDish = $GLOBALS['mysql']->getAll('select * from mr_goods_simple where dish_name = \'\'');
    foreach ($allDish as $val) {
        //refer random
        randSnoopy();
        $href = $val['dish_href'];
        //$href = 'http://www.meiwei123.com/jiachangcai/16081894.htm';
        $GLOBALS['snoopy']->fetch($href);
        $html = str_get_html($GLOBALS['snoopy']->results);
        if (empty($html)) {
            echo 'no html';
            exit;
        }
        $divEl = $html->find('div[id=mainbox]', 0);
        if (empty($divEl)) {
            echo 'no mainbox';
            exit;
        }
        $infoEl = $divEl->find('div.info', 0);
        if (empty($infoEl)) {
            $infoEl = $divEl->find('div.article', 0);
        }
        $data['title'] = $infoEl->find('h1', 0)->plaintext;
        if (empty($data['title'])) {
            echo 'no title';
            exit;
        }
        print_r($data);
        exit;

    }


}

function testDish()
{
    //randSnoopy();
    $href = 'http://www.meiwei123.com/jiachangcai/12267556.htm';
    $GLOBALS['snoopy']->fetch($href);
    $html = str_get_html($GLOBALS['snoopy']->results);
    if (empty($html)) {
        exit('1');
    }
    $divEl = $html->find('div[id=mainbox]', 0);
    if (empty($divEl)) {
        exit('2');
    }
    $imgEl = $divEl->find('div.img', 0);
    if (empty($imgEl)) {
        $imgEl = $divEl->find('p.img', 0);
    }
    $data['img'] = $imgEl->find('img', 0)->src;

    $infoEl = $divEl->find('div.info', 0);
    if (empty($infoEl)) {
        $infoEl = $divEl->find('div.article', 0);
    }
    $data['title'] = $infoEl->find('h1', 0)->plaintext;

    $data['tags'] = '';
    $tagEl = $infoEl->find('p.tags', 0);
    if (!empty($tagEl)) {
        foreach ($tagEl->find('a') as $valTag) {
            $data['tags'] .= $valTag->plaintext . '|';
        }
    }
    $contentEl = $infoEl->find('p.tutor', 0);
    if (empty($contentEl)) {
        $contentEl = $divEl->find('div.content', 0);
    }
    $data['desc'] = $contentEl->plaintext;

    //用料
    $mateArr = array();
    $materialEl = $divEl->find('dl.materials', 0);
    if (!empty($materialEl)) {
        foreach ($materialEl->find('dd') as $el) {
            if (empty($el)) {
                continue;
            }
            $span1 = trim($el->find('span', 0)->plaintext);
            $span2 = trim($el->find('span', 1)->plaintext);
            if (!empty($span1) || !empty($span2)) {
                $mateArr[] = array($span1, $span2);
            }
        }
        $data['materials'] = serialize($mateArr);
    }
    //步骤
    $stepArr = array();
    $stepsEl = $divEl->find('dl.steps', 0);
    if (!empty($stepsEl)) {
        foreach ($stepsEl->find('dd') as $el) {
            if (empty($el)) {
                continue;
            }
            $img = trim($el->find('img', 0)->src);
            $p = trim($el->find('p', 0)->plaintext);
            if (!empty($img) || !empty($p)) {
                $stepArr[] = array($img, $p);
            }
        }
        $data['steps'] = serialize($stepArr);
    }
    //小贴士
    $tipsEl = $divEl->find('dl.tips', 0);
    if (!empty($tipsEl)) {
        $data['tips'] = $tipsEl->find('dd', 0)->plaintext;
    }
    print_r($data);
}

/**
 *
 */
function loadDishDetail()
{
    //$GLOBALS['mysql']->query('delete from mr_goods where 1=1');
    $allDish = $GLOBALS['mysql']->getAll('select * from mr_goods_simple where id >185474');
    foreach ($allDish as $key => $val) {
        //refer random
        randSnoopy();
        $href = $val['dish_href'];
        $GLOBALS['snoopy']->fetch($href);
        $html = str_get_html($GLOBALS['snoopy']->results);
        if (empty($html)) {
            continue;
        }
        $divEl = $html->find('div[id=mainbox]', 0);
        if (empty($divEl)) {
            continue;
        }
        $data['img'] = '';
        $imgEl = $divEl->find('div.img', 0);
        if (empty($imgEl)) {
            $imgEl = $divEl->find('p.img', 0);
        }
        if (!empty($imgEl)) {
            $imgSrcEl = $imgEl->find('img', 0);
            if (!empty($imgSrcEl)) {
                $data['img'] = $imgSrcEl->src;
            }
        }

        $data['title'] = '';
        $data['tags'] = '';
        $data['desc'] = '';
        $infoEl = $divEl->find('div.info', 0);
        if (empty($infoEl)) {
            $infoEl = $divEl->find('div.article', 0);
        }
        if (!empty($infoEl)) {
            $h1El = $infoEl->find('h1', 0);
            if (!empty($imgEl)) {
                $data['title'] = $h1El->plaintext;
            }

            $tagEl = $infoEl->find('p.tags', 0);
            if (!empty($tagEl)) {
                foreach ($tagEl->find('a') as $valTag) {
                    $data['tags'] .= $valTag->plaintext . '|';
                }
            }

            $contentEl = $infoEl->find('p.tutor', 0);
            if (empty($contentEl)) {
                $contentEl = $divEl->find('div.content', 0);
            }
            $data['desc'] = $contentEl->plaintext;
        }
        //用料
        $mateArr = array();
        $materialEl = $divEl->find('dl.materials', 0);
        if (!empty($materialEl)) {
            foreach ($materialEl->find('dd') as $el) {
                if (empty($el)) {
                    continue;
                }
                $span1 = trim($el->find('span', 0)->plaintext);
                $span2 = trim($el->find('span', 1)->plaintext);
                if (!empty($span1) || !empty($span2)) {
                    $mateArr[] = array($span1, $span2);
                }
            }
            $data['materials'] = serialize($mateArr);
        }
        //步骤
        $stepArr = array();
        $stepsEl = $divEl->find('dl.steps', 0);
        if (!empty($stepsEl)) {
            foreach ($stepsEl->find('dd') as $el) {
                if (empty($el)) {
                    continue;
                }
                $img = trim($el->find('img', 0)->src);
                $p = trim($el->find('p', 0)->plaintext);
                if (!empty($img) || !empty($p)) {
                    $stepArr[] = array($img, $p);
                }
            }
            $data['steps'] = serialize($stepArr);
        }
        //小贴士
        $tipsEl = $divEl->find('dl.tips', 0);
        if (!empty($tipsEl)) {
            $data['tips'] = $tipsEl->find('dd', 0)->plaintext;
        }
        $dish = $GLOBALS['mysql']->getRow('select * from mr_goods where goods_simple_id = ' . $val['id'] . '');
        if ($dish) {
            $sql = "UPDATE `mr_goods` SET `goods_name`='" . $data['title'] . "',`goods_img`='" . $data['img'] . "',`keywords`='" . $data['tags'] . "',`goods_desc`='" . $data['desc'] . "',`goods_materials`='" . $data['materials'] . "',`goods_steps`='" . $data['steps'] . "',`goods_tips`='" . $data['tips'] . "' WHERE `goods_id`='" . $dish['goods_id'] . "'";
            $r = $GLOBALS['mysql']->query($sql);
            $t = 'update';
        } else {
            $sql = "INSERT INTO mr_goods (`goods_simple_id`,`goods_name`,`goods_img`,`keywords`,`goods_desc`,`goods_materials`,`goods_steps`,`goods_tips`)VALUES(" . $val['id'] . ",'" . $data['title'] . "','" . $data['img'] . "','" . $data['tags'] . "','" . $data['desc'] . "','" . $data['materials'] . "','" . $data['steps'] . "','" . $data['tips'] . "')";
            $r = $GLOBALS['mysql']->query($sql);
            $t = 'insert';
        }
        if ($r) {
            pp(array((++$key), $data['title'], 'done', $t));
        } else {
            echo("fail\n");
        }
        $html->clear();
    }
}


/**
 * 导入菜谱，并关联
 */
function loadc()
{
    $allCate = $GLOBALS['mysql']->getAll('select * from mr_category');
    foreach ($allCate as $val) {
        $index = 0;
        for ($i = 1; $i < 10000; $i++) {
            //refer random
            randSnoopy();
            if ($i == 1) {
                $href = $val['cat_desc'];
            } else {
                $href = $val['cat_desc'] . 'index_' . $i . '.htm';
            }
            $GLOBALS['snoopy']->fetch($href);
            $html = str_get_html($GLOBALS['snoopy']->results);
            if (empty($html)) {
                break;
            }
            $divEl = $html->find('div.effect', 0);
            if (empty($divEl)) {
                break;
            }
            foreach ($divEl->find('li') as $liEl) {
                $aEl = $liEl->find('a', 0);
                $imgEl = $liEl->find('img', 0);
                if (empty($aEl) || empty($imgEl)) {
                    continue;
                }
                $data['dish_name'] = $aEl->plaintext;
                $data['dish_href'] = $aEl->href;
                if (empty($data['dish_name']) || empty($data['dish_href'])) {
                    continue;
                }
                $data['dish_img'] = $imgEl->src;

                $dish = $GLOBALS['mysql']->getRow('select * from mr_goods_simple where dish_href = \'' . $data['dish_href'] . '\'');
                if ($dish) {
                    $d['goods_id'] = $dish['id'];
                    $t = 'old';
                } else {
                    $sql = "INSERT INTO mr_goods_simple (`dish_name`,`dish_href`,`dish_img`)VALUES('" . $data['dish_name'] . "','" . $data['dish_href'] . "','" . $data['dish_img'] . "')";
                    $r = $GLOBALS['mysql']->query($sql);
                    $d['goods_id'] = $GLOBALS['mysql']->lastid();
                    $t = 'new';
                }
                //插入关系表
                $d['relation_id'] = $val['cat_id'];
                $sql = "INSERT INTO mr_goods_simple_relation (`goods_id`,`relation_id`)VALUES('" . $d['goods_id'] . "','" . $d['relation_id'] . "')";
                $rr = $GLOBALS['mysql']->query($sql);
                if ($r && $rr) {
                    pp(array((++$index), $val['cat_name'], $data['dish_name'], $t));
                } else {
                    echo("fail\n");
                }
            }
            $html->clear();
        }

    }
}

function loadt()
{

    $allCate = $GLOBALS['mysql']->getAll('select * from mr_taste');
    foreach ($allCate as $val) {
        $index = 0;
        for ($i = 1; $i < 10000; $i++) {
            //refer random
            randSnoopy();
            if ($i == 1) {
                $href = $val['cat_desc'];
            } else {
                $href = $val['cat_desc'] . 'index_' . $i . '.htm';
            }
            $GLOBALS['snoopy']->fetch($href);
            $html = str_get_html($GLOBALS['snoopy']->results);
            if (empty($html)) {
                break;
            }
            $divEl = $html->find('div.effect', 0);
            if (empty($divEl)) {
                break;
            }
            foreach ($divEl->find('li') as $liEl) {
                $aEl = $liEl->find('a', 0);
                $imgEl = $liEl->find('img', 0);
                if (empty($aEl) || empty($imgEl)) {
                    continue;
                }
                $data['dish_name'] = $aEl->plaintext;
                $data['dish_href'] = $aEl->href;
                if (empty($data['dish_name']) || empty($data['dish_href'])) {
                    continue;
                }
                $data['dish_img'] = $imgEl->src;

                $dish = $GLOBALS['mysql']->getRow('select * from mr_goods_simple where dish_href = \'' . $data['dish_href'] . '\'');
                if ($dish) {
                    $d['goods_id'] = $dish['id'];
                    $t = 'old';
                } else {
                    $sql = "INSERT INTO mr_goods_simple (`dish_name`,`dish_href`,`dish_img`)VALUES('" . $data['dish_name'] . "','" . $data['dish_href'] . "','" . $data['dish_img'] . "')";
                    $r = $GLOBALS['mysql']->query($sql);
                    $d['goods_id'] = $GLOBALS['mysql']->lastid();
                    $t = 'new';
                }
                //插入关系表
                $d['relation_id'] = $val['cat_id'];
                $d['relation_type'] = 1;
                $sql = "INSERT INTO mr_goods_simple_relation (`goods_id`,`relation_id`,`relation_type`)VALUES('" . $d['goods_id'] . "','" . $d['relation_id'] . "','" . $d['relation_type'] . "')";
                $rr = $GLOBALS['mysql']->query($sql);
                if ($r && $rr) {
                    pp(array((++$index), $val['cat_name'], $data['dish_name'], $t));
                } else {
                    echo("fail\n");
                }
            }
            $html->clear();
        }

    }
}

function loadm()
{

    $allCate = $GLOBALS['mysql']->getAll('select * from mr_material');
    foreach ($allCate as $val) {
        $index = 0;
        for ($i = 1; $i < 1000; $i++) {
            //refer random
            randSnoopy();
            if ($i == 1) {
                $href = $val['cat_desc'];
            } else {
                $href = $val['cat_desc'] . 'index_' . $i . '.htm';
            }
            $GLOBALS['snoopy']->fetch($href);
            $html = str_get_html($GLOBALS['snoopy']->results);
            if (empty($html)) {
                break;
            }
            $divEl = $html->find('div.effect', 0);
            if (empty($divEl)) {
                break;
            }
            foreach ($divEl->find('li') as $liEl) {
                $aEl = $liEl->find('a', 0);
                $imgEl = $liEl->find('img', 0);
                if (empty($aEl) || empty($imgEl)) {
                    continue;
                }
                $data['dish_name'] = $aEl->plaintext;
                $data['dish_href'] = $aEl->href;
                if (empty($data['dish_name']) || empty($data['dish_href'])) {
                    continue;
                }
                $data['dish_img'] = $imgEl->src;

                $dish = $GLOBALS['mysql']->getRow('select * from mr_goods_simple where dish_href = \'' . $data['dish_href'] . '\'');
                if ($dish) {
                    $d['goods_id'] = $dish['id'];
                    $t = 'old';
                } else {
                    $sql = "INSERT INTO mr_goods_simple (`dish_name`,`dish_href`,`dish_img`)VALUES('" . $data['dish_name'] . "','" . $data['dish_href'] . "','" . $data['dish_img'] . "')";
                    $r = $GLOBALS['mysql']->query($sql);
                    $d['goods_id'] = $GLOBALS['mysql']->lastid();
                    $t = 'new';
                }
                //插入关系表
                $d['relation_id'] = $val['cat_id'];
                $d['relation_type'] = 2;
                $sql = "INSERT INTO mr_goods_simple_relation (`goods_id`,`relation_id`,`relation_type`)VALUES('" . $d['goods_id'] . "','" . $d['relation_id'] . "','" . $d['relation_type'] . "')";
                $rr = $GLOBALS['mysql']->query($sql);
                if ($r && $rr) {
                    pp(array((++$index), $val['cat_name'], $data['dish_name'], $t));
                } else {
                    echo("fail\n");
                }
            }
            $html->clear();
        }

    }
}


/**
 * 导入菜系
 */
function loadCategory()
{

    $href = 'http://www.meiwei123.com/jiachangcai/caixi/';
    $GLOBALS['snoopy']->fetch($href);
    $html = str_get_html($GLOBALS['snoopy']->results);
    if (empty($html)) {
        return;
    }
    $ulEl = $html->find('ul.tags', 0);
    if (empty($ulEl)) {
        return;
    }

    $GLOBALS['mysql']->query('delete from mr_category where 1=1');
    foreach ($ulEl->find('li') as $liEl) {
        $aEl = $liEl->find('a', 0);
        if (empty($aEl)) {
            continue;
        }
        $data['cat_name'] = $aEl->plaintext;
        $data['cat_desc'] = $aEl->href;
        $sql = "INSERT INTO mr_category (`cat_name`,`cat_desc`)VALUES('" . $data['cat_name'] . "','" . $data['cat_desc'] . "')";
        $r = $GLOBALS['mysql']->query($sql);
        if ($r) {
            echo("done\n");
        }
    }
    $html->clear();
    echo($href . "\n");
}

/**
 * 导入口味
 */
function loadTaste()
{

    $href = 'http://www.meiwei123.com/jiachangcai/kouwei/';
    $GLOBALS['snoopy']->fetch($href);
    $html = str_get_html($GLOBALS['snoopy']->results);
    if (empty($html)) {
        return;
    }
    $ulEl = $html->find('ul.tags', 0);
    if (empty($ulEl)) {
        return;
    }

    $GLOBALS['mysql']->query('delete from mr_taste where 1=1');
    foreach ($ulEl->find('li') as $liEl) {
        $aEl = $liEl->find('a', 0);
        if (empty($aEl)) {
            continue;
        }
        $data['cat_name'] = $aEl->plaintext;
        $data['cat_desc'] = $aEl->href;
        $sql = "INSERT INTO mr_taste (`cat_name`,`cat_desc`)VALUES('" . $data['cat_name'] . "','" . $data['cat_desc'] . "')";
        $r = $GLOBALS['mysql']->query($sql);
        if ($r) {
            echo("done\n");
        }
    }
    $html->clear();
    echo($href . "\n");
}

/**
 * 导入食材
 */
function loadMaterial()
{

    $href = 'http://www.meiwei123.com/jiachangcai/yongliao/';
    $GLOBALS['snoopy']->fetch($href);
    $html = str_get_html($GLOBALS['snoopy']->results);
    if (empty($html)) {
        return;
    }
    $GLOBALS['mysql']->query('delete from mr_material where 1=1');

    foreach ($html->find('div.materials') as $mEl) {
        $mmEl = $mEl->find('div.left', 0);
        if (empty($mmEl)) {
            return;
        }
        $data['cat_name'] = $mmEl->plaintext;
        $data['cat_desc'] = '';
        $sql = "INSERT INTO mr_material (`cat_name`,`cat_desc`)VALUES('" . $data['cat_name'] . "','" . $data['cat_desc'] . "')";
        $r = $GLOBALS['mysql']->query($sql);
        $id = $GLOBALS['mysql']->lastid();
        foreach ($mEl->find('li') as $liEl) {
            $aEl = $liEl->find('a', 0);
            if (empty($aEl)) {
                continue;
            }
            $imgEl = $liEl->find('img', 0);
            $data['keywords'] = $imgEl->meiwei;
            $data['cat_name'] = $aEl->plaintext;
            $data['cat_desc'] = $aEl->href;
            $data['parent_id'] = $id;
            $sql = "INSERT INTO mr_material (`cat_name`,`cat_desc`,`parent_id`,`keywords`)VALUES('" . $data['cat_name'] . "','" . $data['cat_desc'] . "','" . $data['parent_id'] . "','" . $data['keywords'] . "')";
            $r = $GLOBALS['mysql']->query($sql);
            if ($r) {
                echo("done\n");
            }
        }

    }
    $html->clear();
    echo($href . "\n");
}

