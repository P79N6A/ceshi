@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="ideaManage-create" class="pageName"></div>
    <div class="appCon">
        <!--新建创意-->
        <div class="mybox ideaCreate_JS">
            <div class="TitleBar">
                <div class="floatL ML20"><a href="/app/creatives" target="_blank" class="ideaCreateLink"> <b class="ori">
     创意管理 </b></a><span>> 新建微博创意</span></div>
                <div class="floatR MR20">
                </div>
                <div class="blankBox"></div>
            </div>
            <div class="myboxCon width1066">
                <div style="vertical-align:top;" class="myboxConL">
                    <div class="myboxConLCon myb2">
                        <div class="adInputBox clearfix MB10"><span class="floatL adInputTitle">选择应用:</span>
                            <select class="floatL textBlack W320 fillAppName_JS"></select>
                        </div>
                        <div class="adInputBox clearfix MB10"><span class="floatL adInputTitle weiboContentTit">微博正文:</span><span class="floatL adTextCon">
                        <textarea  cols="25" rows="5" style="width:220px" class="color000 adTextLimit weiboContext_JS" placeholder="最少输入10个汉字，最多不超过30个汉字，且文案中不能包含链接"></textarea></span></div>
                        <div id="pl_content_publisherTop" class="send_weibo send_weibo_current upcssxm">
                            <div node-type="widget" class="kind W_linkb">
                                <div class="deleteImgMark deleteImgMark_JS"></div> 
                                <div style="width:260px;height:130px;background:#f3f3f3" action-type="image" class="thisImgCon_JS"  suda-uatrack="key=tblog_appllo_project&value=new_creative_image">
                                
                                    <div class="imgTC1"> <span class="ori">+ </span>添加图片</div>
                                    <div class="imgTC2">518 * 259</div>
                                    <div class="imgTC3">大小&lt;=60K；格式.png或.jpg</div><img src="" class="adUploadShow" style="display:none">

                                </div>
                            </div>
                        </div>
                        <!-- zlh新增加的左边预览新增加的-->
                        <!--zlh  新增 九宫格样式2-->
                        <div class="ninebox-styles upcssxm MB20" id="nineboxLeftList">
                             <ul class="ninebox-widge">
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                                  <li class="add"><img src="" data-view-img/><div class="ninebox-bg"></div><span class="ninebox-edit" action-type="ninebox-addPic"></span><span class="ninebox-close"></span><span class="ninebox-add" action-type="ninebox-addPic"></span></li>
                             </ul>
                        </div>
                        <div class="ninebox-shadow-cover" ></div>    
                        <div class="ninebox-showBox ninebox-editShowBox">
                             <a class="ninebox-editShowBox-closeBtn" href="javascript:void(0)" action-type="ninebox-close"></a> 
                             <p class="ninebox-title">添加图片.标签</p>
                             <div class="ninebox-publishBox">
                                  <div class="ninebox-publishBox-img ninebox-hasImg-JS"  style="display:block;">
                                       <img src="ninebox-com.png" data-success-img/>
                                       <div class="ninebox-publishBox-marklist" id="ninebox-mark-info">
                                                <span class="ninebox-mark-leftbar"></span>
                                                <p class="ninebox-mark-box">
                                                     <span class="ninebox-mark-box-ti ninebox-mark-JS">立即下载</span>
                                                     <span class="ninebox-mark-box-bg"></span>
                                                </p>
                                       </div>
                                  </div>
                                  
                                  <div class="ninebox-publishBox-default ninebox-noImg-JS" style="display:none;">
                                      <p class="ninebox-publishBox-default-a">上传图片</p>
                                      <p class="ninebox-publishBox-default-b">1024*1024</p>
                                      <p class="ninebox-publishBox-default-c">大小不超过60KB，格式 .png 或 .jpg</p>
                                  </div>
                                  <a class="ninebox-publishBtn" href="javascript:void(0)" id="ninebox-publishImg">上传图片</a>
                             </div>
                             <div class="ninebox-mark">
                                  <div class="ninebox-markTit">标签文案<span class="ninebox-markTit-tips"> ( 鼠标拖动标签可改变标签的位置 ) </span><span class="questionMark tips_JS" title="鼠标拖动标签可改变标签的位置"></span></div>
                                  <div class="ninebox-marklist" id="ninebox-marklist">
                                      <ul>
                                          <li data-mark-style="1" data-mark-ti="立即下载" class="cur"><span class="ninebox-marklist-icon"></span>立即下载</li>
                                          <li data-mark-style="2" data-mark-ti="立即打开"><span class="ninebox-marklist-icon"></span>立即打开</li>
                                          <li data-mark-style="3" data-mark-ti="立即上传"><span class="ninebox-marklist-icon"></span>立即上传</li>
                                          <li data-mark-style="4" data-mark-ti="立即更新"><span class="ninebox-marklist-icon"></span>立即更新</li>
                                          <li data-mark-style="5" data-mark-ti="立即删除"><span class="ninebox-marklist-icon"></span>立即删除</li>
                                          <li data-mark-style="6" data-mark-ti="图片大小"><span class="ninebox-marklist-icon"></span>图片大小</li>
                                          <li data-mark-style="7" data-mark-ti="立即调整"><span class="ninebox-marklist-icon"></span>立即调整</li>
                                          <li data-mark-style="8" data-mark-ti="立即跳转"><span class="ninebox-marklist-icon"></span>立即跳转</li>
                                      </ul>
                                  </div> 
                             </div>
                             <div class="ninebox-bottom">
                                 <a class="confirm-btn" herf="javascript:void(0)" id="ninebox-confirm">确定</a>
                                 <a class="cancle-btn" herf="javascript:void(0)"  action-type="ninebox-cancle">取消</a>
                             </div>
                        </div>
                        <div class="ninebox-showBox ninebox-viewBox" style="display:none">
                             <a class="ninebox-editShowBox-closeBtn" href="javascript:void(0)" action-type="ninebox-close"></a> 
                             <p class="ninebox-title">预览</p>
                             <div class="ninebox-publishBox">
                                 <div class="ninebox-publishBox-img">   
                                    <img src="ninebox-com.png" class="ninebox-view-img-JS"/>
                                    <div class="ninebox-publishBox-marklist" >
                                                <span class="ninebox-mark-leftbar"></span>
                                                <p class="ninebox-mark-box">
                                                     <span class="ninebox-mark-box-ti ninebox-mark-JS">立即下载</span>
                                                     <span class="ninebox-mark-box-bg"></span>
                                                </p>
                                    </div>
                                </div>
                             </div>
                           
                             <div class="ninebox-bottom">
                                 <a class="confirm-btn" herf="javascript:void(0)" action-type="ninebox-cancle">确定</a>
                             </div>
                        </div>
                        <div class="clearfix"></div>
                        <!--zlh左边新增加的结束-->
                        <style type="text/css">.layer_send_pic .lapic_edit .beautify{display: none;}</style>
                        <!--<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/theia_1_1.js?v={{ getenv('STATIC_VERSION') }}" type="text/javascript"></script>
                        <script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/publisherTop.js?v={{ getenv('STATIC_VERSION') }}" type="text/javascript"></script>  -->
                        <script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/uploadImg/ajaxForm.js" type="text/javascript"></script>
                        <script src="http://js.t.sinajs.cn/weiboad/apps/app//js/apps/nineboxPic.js" type="text/javascript"></script>  
                        
                        <div class="adInputBox clearfix MB10"> <span class="floatL adInputTitle">图片属性1:</span>
                            <select class="floatL color000 W320 imgSrc1_JS">
                                <option value="1" selected="selected">应用名称</option>
                                <option value="2">自定义</option>
                            </select>
                            <input type="text" placeholder="" class="adInputInput floatL color000 W300 imgSrc_JS myHide MT10"/>
                        </div>
                        <div class="adInputBox clearfix MB10"><span class="floatL adInputTitle">图片属性2:</span>
                            <select class="floatL color000 W320 imgSrc2_JS">
                                <option value="1" selected="selected">应用分类</option>
                                <option value="2">安装包大小</option>
                                <option value="3">应用评分</option>
                                <option value="4">下载量</option>
                                <option value="5">自定义</option>
                            </select>
                        </div>
                        <input type="text" placeholder="自定义" class="adInputInput ML77 MB5 W300 imgSrc22_JS myHide"/>
                        <div class="adInputBox clearfix MB10"><span class="floatL adInputTitle">评论:</span>
                            <div class="floatL color000">
                                <input id="card-image" type="radio" name="adComment" checked="checked" value="0"/>
                                <label for="card-image ">开启</label>
                                <input id="mul-image" type="radio" name="adComment" value="1" class="ML15"/>
                                <label for="mul-image">关闭</label>
                            </div>
                        </div>
                        <div class="adInputBox clearfix MB10"><span class="floatL adInputTitle">创意名称:</span>
                            <input type="text" placeholder="创意名称" class="adInputInput floatL color000 W300 ideaName_JS"/>
                        </div>
                    </div>
                </div>
                <div style="vertical-align:top;" class="myboxConR">
                    <div class="myboxConCon myb2">
                        <div class="adShowApp">
                            <div class="adShowAppBox">
                                <p class="adShowHeader textBlack FS14">效果预览</p>
                                <p class="adShowSuggest">推荐</p>
                                <div class="adShowAppBody">
                                    <div class="MB10"><img src="http://js.t.sinajs.cn/weiboad/apps/app/images/icon_user_logo.jpeg" alt="" width="40" height="40" class="icon_JS1"/>
                                        <div>
                                            <p class="adShowAppName color000 FS14 name_JS">Loading...</p>
                                            <p class="adShowAppSource"><span class="adShowAppTime">

                                  1分钟前</span><span class="adShowAppLocation">来自weibo.com</span></p>
                                        </div>
                                    </div>
                                    <p class="FS16 color000 MB10 text_JS">Loading...</p>
                                    <div class="imgs_JS"><img src="http://js.t.sinajs.cn/weiboad/apps/app/images/icon_app_text.png" width="321px" height="160px"/></div>
                                    <!--zlh右边的新增开始-->
                                    <div class="ninebox_imgs_JS"></div>
                                    <!--zlh右边的新增的结束-->
                                    <div class="adShowAppDownload MT10 FS14">
                                        <div class="adShowAppDownloadIcon"></div>
                                        <p class="color000 imgs_JS2">Loading...</p>
                                        <p class="src_JS">Loading...</p>
                                        <div data-rateit-value="2.5" data-rateit-ispreset="true" data-rateit-readonly="true" class="src2_JS myHide rateit"></div>
                                    </div>
                                </div>
                                <div class="adShowAppContact">
                                    <div>
                                        <p class="adShowAppContactImg"><span class="adShowAppContactForward FS14">0</span></p>
                                        <p class="adShowAppContactImg"><span class="adShowAppContactComment FS14">0</span></p>
                                        <p class="adShowAppContactLast"><span class="adShowAppContactLike FS14">0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottomBar myShadow">
                <div class="btnConW210 centerT10">
                    <div class="myBtn redBtn myShadow floatL submitIdea_JS"  suda-uatrack="key=tblog_appllo_project&value=new_creative_submit">提交审核</div>
                    <div class="myBtn myShadow floatR back_JS">
                        返回
                    </div>
                    <div class="blankBox"></div>
                </div>
            </div>
        </div>
        <!--没有创意    -->
        <div class="noAPP myHide noAPP_JS">
            <div class="tanhao"></div><br/>创意需要和所推广的应用关联，您目前没有添加任何应用，无法新建创意<br/><br/><a href="/app/?customer_id={{ UserInfo::getTargetUserId() }}" >去添加应用</a>
        </div>
    </div>
</div>
@stop

@section('data')
bee.appId = {{ empty($app_id) ? '0': $app_id }};
@stop