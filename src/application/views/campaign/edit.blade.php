@extends('layouts.default')

@section('content')


<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="planEdit" class="pageName"></div>
    <div class="appCon ideaBox_JS">
        <div class="appBar MB10">
            <div class="logoAndText centerT10 MB15"><img src="http://js.t.sinajs.cn/weiboad/apps/app//images/app-name.png" alt="" class="logoAndTextImg logoAndTextImg_JS MB15"/>
                <div class="FCenter"> <b>Loading...</b></div>
            </div>
        </div>
        <!--推广创意-->
        <div class="mybox">
            <div class="TitleBar">
                <div class="floatL ML20"><b>推广创意</b></div>
                <div class="blankBox"></div>
            </div>
            <div class="myboxCon width1066">
                <div style="vertical-align:top;" class="myboxConL">
                    <div class="myboxConLCon myb2">
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">选择创意:</span>
                            <select class="floatL textBlack fillIdeaName_JS"></select>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">微博正文:</span>
                            <p class="floatL adInputContent textBlack MT8 ideaContent_JS">Loading...</p>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">卡片描述:</span>
                            <p class="floatL adInputContent textBlack"><img src="http://js.t.sinajs.cn/weiboad/apps/app//images/icon_app_text.png" alt="" style="width:255px;height:127px;" class="ideaImages_JS"/></p>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">卡片描述:</span>
                            <div class="floatL color000 adInputContent ideaDisplayName_JS">Loading...</div>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">&nbsp;</span>
                            <div class="floatL color000 adInputContent ideaSummeryE_JS">Loading...</div>
                        </div>
                        <!-- <div class="adInputBox clearfix"><span class="floatL adInputTitle">评论状态:</span>
                            <div class="floatL color000 adInputContent"><span class="commentStatus_JS">开启</span><a href="#" class="adInputChangeComment colorf00">更改评论状态</a>
                            </div>
                        </div> -->
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">评论状态:</span>
                            <div class="floatL color000 adInputContent"><span class="commentStatus_JS">开启 </span><a
                                class="adShowTableStatusBtn adShowTablePauseBtn ML5"></a><span
                                class="changeCommentStatus_JS adInputChangeComment colorf00 myHide">更改评论状态</span>
                            </div>
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
                                    <div class="MB10"><img src="http://js.t.sinajs.cn/weiboad/apps/app//images/icon_user_logo.jpeg" alt="" width="40" height="40" class="icon_JS1"/>
                                        <div>
                                            <p class="adShowAppName color000 FS14 name_JS">Loading...</p>
                                            <p class="adShowAppSource"><span class="adShowAppTime">

                                  1分钟前</span><span class="adShowAppLocation">来自weibo.com</span></p>
                                        </div>
                                    </div>
                                    <p class="FS16 color000 MB10 text_JS">Loading...</p>
                                    <div class="imgs_JS"><img src="http://js.t.sinajs.cn/weiboad/apps/app//images/icon_app_text.png" width="321px" height="160px"/></div>
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
        </div>
        <!--定向设置-->
        <div class="mybox MT10">
            <div class="TitleBar">
                <div class="floatL ML20"><b>定向设置</b></div>
                <div class="blankBox"></div>
            </div>
            <div class="myboxCon width1066 planSetup_JS">
                <div style="vertical-align:top;" class="myboxConL">
                    <div class="myboxConLCon myb2">
                        <div style="overflow:visible" class="adInputBox clearfix"><span class="floatL adInputTitle"><b class="floatL"> </b>粉丝关系:</span>
                            <div class="floatL textBlack adInputContent fensiInputContent">
                                <input id="card-image" type="radio" name="fans" checked="checked" value="-1"/>
                                <label for="card-image">不限</label>
                                <input id="card-image" type="radio" name="fans" value="601" class="ML15"/>
                                <label for="card-image">我的粉丝</label>
                                <input id="mul-image" type="radio" name="fans" value="602" class="ML10"/>
                                <label for="mul-image">指定账户相似粉丝</label>
                            </div>
                            <!--这部分是粉丝选择部分-->
                            <div class="adplan-fans adplan-atom">
                                <div id="FansWrap" class="adplan-atc">
                                    <div id="fansPicker" style="position:relative;" class="input_pop user_input floatL ML10">
                                        <input id="fansInput" type="text" placeholder="请输入账户昵称" class="ui-text myHide adInputInput"/>
                                        <div id="fansDropLayer" style="z-index:999;left:0px;" class="fansDropLayer"></div>
                                    </div>
                                    <div class="blankBox"></div>
                                </div>
                                <!--fanspicker -->
                                <div id="fansPicked" class="fansPicked myHide">
                                    <div class="pickedCount">已选择<strong id="fansPickedCount">0</strong>个指定帐号</div>
                                    <ul id="fansPickedList" class="pickedList"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle"><b class="floatL"> </b>年龄:</span>
                            <div class="floatL textBlack adInputContent">
                                <input id="card-image" type="radio" name="age" checked="checked" value="-1" text-data="不限"/>
                                <label for="age">不限</label>
                                <input id="card-image" type="radio" name="age" value="customize" text-data="自定义" class="ML15"/>
                                <label for="age">自定义</label><span class="ageSelect_JS myHide">
                          <input id="AgeStart" data-age="min" maxlength="2" type="text" style="width:30px" class="adInputInput"/>-
                          <input id="AgeEnd" data-age="max" maxlength="2" type="text" style="width:30px" class="adInputInput"/><span class="errorField_JS ori"> </span><span id="ageErrBox" class="ori"></span></span>
                            </div>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle"><b class="floatL"> </b>性别:</span>
                            <div class="floatL textBlack adInputContent">
                                <input id="card-image" type="radio" name="gender" value="-1" text-data="不限" checked="checked"/>
                                <label for="gender">不限</label>
                                <input id="card-image" type="radio" name="gender" value="401" text-data="男" class="ML15"/>
                                <label for="gender">男</label>
                                <input id="mul-image" type="radio" name="gender" value="402" text-data="女" class="ML15"/>
                                <label for="mul-image">女</label>
                            </div>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle"><b class="floatL"> </b>地域:</span>
                            <div class="floatL textBlack adInputContent" id="RegionWrap">
                                <input id="card-image" type="radio" name="location" checked="checked" value="-1" text-data="不限"/>
                                <label for="location">不限</label>
                                <input id="card-image" type="radio" name="location" value="999" text-data="仅限中国大陆" class="ML15"/>
                                <label for="location">仅限中国大陆</label>
                                <input id="mul-image" type="radio" name="location" value="customize" text-data="自定义" class="ML15"/>
                                <label for="location">自定义</label>
                            </div>
                        </div>
                        <div id="locationPicker" class="adInputBox clearfix ML10 MB20 myHide">
                            <div id="AdplanForm" class="adplan-form">
                                <div class="adplan-atci adplan-diyRegions clearfix">
                                    <div class="fll addlist-outer">
                                        <div id="RegionInput" class="inputWrap fll">
                                            <input id="LocationInput" type="text" placeholder="可拼音或中文输入" class="ui-text adInputInput"/><span class="icon icon-exp"> </span>
                                        </div>
                                        <!-- 已选中省列表-->
                                        <div id="RegionList" class="addlist fll">          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle lineHeight30">系统版本:</span>
                            <select id="device" class="floatL adInputSelect textBlack">
                                <option value="130101" text-data="iOS2.0及以上">iOS2.0</option>
                                <option value="130102" text-data="iOS3.0及以上" selected="selected">iOS3.0</option>
                                <option value="130103" text-data="iOS4.0及以上">iOS4.0</option>
                                <option value="130104" text-data="iOS4.3及以上">iOS4.3</option>
                                <option value="130105" text-data="iOS5.0及以上">iOS5.0</option>
                                <option value="130106" text-data="iOS6.0及以上">iOS6.0</option>
                                <option value="130107" text-data="iOS7.0及以上">iOS7.0</option>
                                <option value="130108" text-data="iOS7.1及以上">iOS7.1</option>
                                <option value="130109" text-data="iOS8.0及以上">iOS8.0</option>
                                <option value="130110" text-data="iOS8.1及以上">iOS8.1</option>
                                <option value="130111" text-data="iOS8.2及以上">iOS8.2</option>
                                <option value="130112" text-data="iOS8.3及以上">iOS8.3</option>
                                <option value="130113" text-data="iOS8.4及以上">iOS8.4</option>
                                <option value="130201" text-data="Android2.0及以上">Android2.0 </option>
                                <option value="130202" text-data="Android2.1及以上">Android2.1</option>
                                <option value="130203" text-data="Android2.2及以上">Android2.2</option>
                                <option value="130204" text-data="Android2.3及以上">Android2.3</option>
                                <option value="130205" text-data="Android3.0及以上">Android3.0</option>
                                <option value="130206" text-data="Android3.1及以上">Android3.1</option>
                                <option value="130207" text-data="Android3.2及以上">Android3.2</option>
                                <option value="130208" text-data="Android4.0及以上">Android4.0</option>
                                <option value="130209" text-data="Android4.1及以上">Android4.1</option>
                                <option value="130210" text-data="Android4.2及以上">Android4.2</option>
                                <option value="130211" text-data="Android4.3及以上">Android4.3 </option>
                                <option value="130212" text-data="Android4.4及以上">Android4.4</option>
                                <option value="130213" text-data="Android5.0及以上">Android5.0</option>
                                <option value="130214" text-data="Android5.1及以上">Android5.1</option>
                            </select><span class="lineHeight30">以上</span>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">网络环境:</span>
                            <div class="floatL textBlack adInputContent">
                                <input type="checkbox" name="netWork" value="1204" text-data="WiFi"/>
                                <label for="network" class="MR10">WiFi</label>
                                <input type="checkbox" name="netWork" value="1203" text-data="3G"/>
                                <label for="network" class="MR10">3G</label>
                                <input type="checkbox" name="netWork" value="1205" text-data="4G"/>
                                <label for="network" class="MR10">4G</label>
                                <input type="checkbox" name="netWork" value="1200" text-data="其他"/>
                                <label for="network">其他</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="vertical-align:top;" class="myboxConR">
                    <div class="myboxConCon myb2">
                        <p class="adShowHeader color000 FS14">已选条件</p>
                        <div class="adShowTableList">
                            <div class="adShowList clearfix"><span class="floatL adShowTableTitle">粉丝关系：</span>
                                <p class="floatL color000 adShowContent planSetup_fans_JS">-</p>
                            </div>
                            <div class="adShowList clearfix"><span class="floatL adShowTableTitle">年龄：</span>
                                <p class="floatL color000 adShowContent planSetup_age_JS">-</p>
                            </div>
                            <div class="adShowList clearfix"><span class="floatL adShowTableTitle">性别：</span>
                                <p class="floatL color000 adShowContent planSetup_gender_JS">-</p>
                            </div>
                            <div class="adShowList clearfix"><span class="floatL adShowTableTitle">地域：</span>
                                <p class="floatL color000 adShowContent planSetup_location_JS">-</p>
                            </div>
                            <div class="adShowList clearfix"><span class="floatL adShowTableTitle">系统版本：</span>
                                <p class="floatL color000 adShowContent planSetup_device_JS">-</p>
                            </div>
                            <div class="adShowList clearfix"><span class="floatL adShowTableTitle">网络环境：</span>
                                <p class="floatL color000 adShowContent planSetup_netWork_JS">-</p>
                            </div>
                        </div>
                        <div class="adShowUser">
                            <p class="color000 FS14">您选择的条件共覆盖了</p>
                            <p><b class="adShowUserNumber">Loading...</b><span>个活跃用户</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--广告预算-->
        <div class="mybox MT10">
            <div class="TitleBar">
                <div class="floatL ML20"><b>广告预算</b></div>
                <div class="blankBox"></div>
            </div>
            <div class="myboxCon width1066 budgetBox_JS">
                <div style="vertical-align:top;">
                    <div class="myboxConLCon myb2">
                        <div class="adInputBox clearfix MB5"><span class="floatL adInputTitle lineHeight30">计划名称:</span>
                            <input type="text" name="PlanName" class="floatL adInputInput textBlack thisPlanName_JS"/>
                        </div>
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">日限额:</span>
                            <div class="floatL textBlack">
                                <input type="radio" name="dayLimited" checked="checked" value="-1"/>
                                <label for="network-environment">不限</label>
                                <input type="radio" name="dayLimited" value="customize" class="ML15"/>
                                <label for="network-environment">自定义 </label><span class="dayLimitedInput_JS myHide">
                          <input type="text" class="adInputInput textBlack ML5 dayLimitedValue_JS"/><span>元/天</span><span class="ori modInfo_JS myHide">(注：修改后，第二天才生效）</span></span>
                            </div>
                            <i title="指在投放周期内每天所要消耗的上限（单位：元）。默认为不限。" class="questionMark MT8 tips_JS"> </i>
                        </div>
                        <!--日限额消耗提示开始--> 
                        <div class="adInputBox clearfix changePriceBox_JS" style="display:none;">
                            <span class="floatL adInputTitle">&nbsp;&nbsp;&nbsp;</span>
                            <span class="floatL changPricetips_JS">日限额修改次日生效，生效限额为<span class="tomPriceDetail_JS"></span>元</span>
                        </div>
                        <!--日限额消耗提示结束-->
                        <div class="adInputBox clearfix"><span class="floatL adInputTitle">设置出价:</span><span class="floatL">CPM</span><i title="自动竞价，为增加移动应用安装而优化，我们会将广告展示给更可能安装应用的用户" class="questionMark MT8 tips_JS"></i></div>
                        <div class="adInputBox clearfix"> <span class="floatL adInputTitle">&nbsp;</span>
                            <!--<div class="floatL textBlack">
                                <input type="radio" name="orderPrice" checked="checked" value="-1"/>
                                <label for="network-environment">不限</label>
                                <input type="radio" name="orderPrice" value="customize" class="ML15"/>
                                <label for="network-environment">自定义 </label><span class="orderPriceBox_JS myHide">
                                <input type="text" name="orderPrice" class="adInputInput textBlack ML5 orderPrice_JS"/><span>元/千次曝光 </span><span class="ML15">最低出价20元，最高出价1000元    </span></span>
                            </div>
-->
                            <div class="floatL textBlack"><span class="orderPriceBox_JS">
                                <input type="text" name="orderPrice" class="adInputInput textBlack ML5 orderPrice_JS"/><span>元/千次曝光(最高出价) </span><span class="ML15"></span><span style='color:#f29d36'>最低出价20元，最高出价1000元</span>     </span>
                            </div>

                        </div>
                        <div class="adInputBox clearfix MB10"><span class="floatL adInputTitle"><b class="floatL"> </b>计划周期:</span>
                            <div class="floatL textBlack">
                                <input id="card-image" type="radio" name="planTime" checked="checked" value="-1"/>
                                <label for="card-image">立即开始，长期投放</label>
                                <input id="card-image" type="radio" name="planTime" value="customize" class="ML15"/>
                                <label for="card-image">自定义</label><i title="指广告计划投放的时间段设定。或“立即开始、长期投放”；或自定义周期。默认为“立即开始、长期投放”。" class="questionMark tips_JS"></i>
                                <sapn class="planTimeInput_JS myHide">
                                    <input id="startDate" type="text" name="date-from" style="width:70px" class="adInputInput"/>
                                    <select style="width:50px" class="startTimeH_JS">
                                        <option value="00" selected="selected">00</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                    </select>
                                    <select style="width:50px" class="startTimeM_JS">
                                        <option value="00" selected="selected">00</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select>-
                                    <input id="endDate" type="text" name="date-to" style="width:70px" class="adInputInput"/>
                                    <select style="width:50px" class="endTimeH_JS">
                                        <option value="00" selected="selected">00</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                    </select>
                                    <select style="width:50px" class="endTimeM_JS">
                                        <option value="00" selected="selected">00</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select>
                                </sapn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mybox MT10">
            <div class="TitleBar MB10">
                <div class="floatL ML20"><b>监控设置</b></div>
                <div class="blankBox"></div>
            </div>
            <div class="adInputBox clearfix MB10 fish1_JS"><span style="width:100px" class="floatL adInputTitle"><b class="floatL"> </b>监控地址:</span>
                <input placeholder="输入应用转化监控地址" class="floatL adInputInput textBlack talkingData_JS"/><i title="请填写您在第三方生成的监控地址，目前支持TalkingData和AdMaster转换大师的监控地址。</br>应用家将根据该地址，获得您所推广的应用版本激活情况。" class="questionMark MT8 tips_JS"> </i>
            </div>
        </div>
        <div class="btnLine MT10">
            <div class="btnConW226 centerT10">
                <div class="myBtn redBtn sBtn myShadow floatL submitPlan_JS" suda-uatrack="key=tblog_appllo_project&value=plan_edit_submit">提交</div>
                <div class="myBtn sBtn myShadow floatR back_JS">返回上一步</div>
                <div class="myBtn sBtn myShadow floatR MR5 savePlan_JS">保存草稿</div>
                <div class="blankBox"></div>
            </div>
        </div>
    </div>
    <div class="mybox noIdeaBox_JS myHide">
        <div class="TitleBar">
            <div class="floatL ML20"><b>推广创意</b></div>
            <div class="blankBox"></div>
        </div>
        <div class="noAPP">
            <div class="tanhao"></div><br/>暂无可用创意<br/><br/><a href="/app/creatives/create?customer_id={{ \UserInfo::getTargetUserId() }}"  suda-uatrack="key=tblog_appllo_project&value=immediately_new_creative">立即创建创意</a>
        </div>
    </div>
    <!--这个是弹框-->
    <div id="Regions" class="regions tree root node">
        <p class="clearfix opt"><span class="flr"><span class="icon-close">X</span></span></p>
        <table class="table">
            <tbody>
            <tr class="node">
                <td colspan="6">
                    <dl>
                        <dt>
                            <label for="East">
                                <input id="East" data-parent="-1" type="checkbox" value="East"/>东部沿海
                            </label>
                        </dt>
                        <dd><span class="node leaf">
                          <label for="Location302">
                              <input id="Location302" data-parent="East" type="checkbox" data-value="北京" value="302" name="province"/>北京
                          </label></span><span class="node leaf">
                          <label for="Location303">
                              <input id="Location303" data-parent="East" type="checkbox" data-value="上海" value="303" name="province"/>上海
                          </label></span><span class="node leaf">
                          <label for="Location301">
                              <input id="Location301" data-parent="East" type="checkbox" data-value="广东" value="301" name="province"/>广东
                          </label></span><span class="node leaf">
                          <label for="Location311">
                              <input id="Location311" data-parent="East" type="checkbox" data-value="辽宁" value="311" name="province"/>辽宁
                          </label></span><span class="node leaf">
                          <label for="Location316">
                              <input id="Location316" data-parent="East" type="checkbox" data-value="天津" value="316" name="province"/>天津
                          </label></span><span class="node leaf">
                          <label for="Location312">
                              <input id="Location312" data-parent="East" type="checkbox" data-value="河北" value="312" name="province"/>河北
                          </label></span><span class="node leaf">
                          <label for="Location306">
                              <input id="Location306" data-parent="East" type="checkbox" data-value="山东" value="306" name="province"/>山东
                          </label></span><span class="node leaf">
                          <label for="Location305">
                              <input id="Location305" data-parent="East" type="checkbox" data-value="江苏" value="305" name="province"/>江苏
                          </label></span><span class="node leaf">
                          <label for="Location304">
                              <input id="Location304" data-parent="East" type="checkbox" data-value="浙江" value="304" name="province"/>浙江
                          </label></span><span class="node leaf">
                          <label for="Location307">
                              <input id="Location307" data-parent="East" type="checkbox" data-value="福建" value="307" name="province"/>福建
                          </label></span><span class="node leaf">
                          <label for="Location328">
                              <input id="Location328" data-parent="East" type="checkbox" data-value="海南" value="328" name="province"/>海南
                          </label></span></dd>
                    </dl>
                </td>
            </tr>
            <tr class="node">
                <td colspan="6">
                    <dl>
                        <dt>
                            <label for="Middle">
                                <input id="Middle" data-parent="-1" type="checkbox" value="Middle"/>中部地区
                            </label>
                        </dt>
                        <dd><span class="node leaf">
                          <label for="Location319">
                              <input id="Location319" data-parent="Middle" type="checkbox" data-value="黑龙江" value="319" name="province"/>黑龙江
                          </label></span><span class="node leaf">
                          <label for="Location321">
                              <input id="Location321" data-parent="Middle" type="checkbox" data-value="吉林" value="321" name="province"/>吉林
                          </label></span><span class="node leaf">
                          <label for="Location326">
                              <input id="Location326" data-parent="Middle" type="checkbox" data-value="内蒙古" value="326" name="province"/>内蒙古
                          </label></span><span class="node leaf">
                          <label for="Location323">
                              <input id="Location323" data-parent="Middle" type="checkbox" data-value="山西" value="323" name="province"/>山西
                          </label></span><span class="node leaf">
                          <label for="Location310">
                              <input id="Location310" data-parent="Middle" type="checkbox" data-value="河南" value="310" name="province"/>河南
                          </label></span><span class="node leaf">
                          <label for="Location317">
                              <input id="Location317" data-parent="Middle" type="checkbox" data-value="安徽" value="317" name="province"/>安徽
                          </label></span><span class="node leaf">
                          <label for="Location309">
                              <input id="Location309" data-parent="Middle" type="checkbox" data-value="湖北" value="309" name="province"/>湖北
                          </label></span><span class="node leaf">
                          <label for="Location313">
                              <input id="Location313" data-parent="Middle" type="checkbox" data-value="湖南" value="313" name="province"/>湖南
                          </label></span><span class="node leaf">
                          <label for="Location320">
                              <input id="Location320" data-parent="Middle" type="checkbox" data-value="江西" value="320" name="province"/>江西
                          </label></span><span class="node leaf">
                          <label for="Location315">
                              <input id="Location315" data-parent="Middle" type="checkbox" data-value="广西" value="315" name="province"/>广西
                          </label></span></dd>
                    </dl>
                </td>
            </tr>
            <tr class="node">
                <td colspan="6">
                    <dl>
                        <dt>
                            <label for="West">
                                <input id="West" data-parent="-1" type="checkbox" value="West"/>西部地区
                            </label>
                        </dt>
                        <dd><span class="node leaf">
                          <label for="Location314">
                              <input id="Location314" data-parent="West" type="checkbox" data-value="陕西" value="314" name="province"/>陕西
                          </label></span><span class="node leaf">
                          <label for="Location331">
                              <input id="Location331" data-parent="West" type="checkbox" data-value="宁夏" value="331" name="province"/>宁夏
                          </label></span><span class="node leaf">
                          <label for="Location330">
                              <input id="Location330" data-parent="West" type="checkbox" data-value="甘肃" value="330" name="province"/>甘肃
                          </label></span><span class="node leaf">
                          <label for="Location333">
                              <input id="Location333" data-parent="West" type="checkbox" data-value="青海" value="333" name="province"/>青海
                          </label></span><span class="node leaf">
                          <label for="Location318">
                              <input id="Location318" data-parent="West" type="checkbox" data-value="重庆" value="318" name="province"/>重庆
                          </label></span><span class="node leaf">
                          <label for="Location308">
                              <input id="Location308" data-parent="West" type="checkbox" data-value="四川" value="308" name="province"/>四川
                          </label></span><span class="node leaf">
                          <label for="Location324">
                              <input id="Location324" data-parent="West" type="checkbox" data-value="贵州" value="324" name="province"/>贵州
                          </label></span><span class="node leaf">
                          <label for="Location322">
                              <input id="Location322" data-parent="West" type="checkbox" data-value="云南" value="322" name="province"/>云南
                          </label></span><span class="node leaf">
                          <label for="Location329">
                              <input id="Location329" data-parent="West" type="checkbox" data-value="新疆" value="329" name="province"/>新疆
                          </label></span><span class="node leaf">
                          <label for="Location334">
                              <input id="Location334" data-parent="West" type="checkbox" data-value="西藏" value="334" name="province"/>西藏
                          </label></span></dd>
                    </dl>
                </td>
            </tr>
            <tr class="node">
                <td colspan="6">
                    <dl>
                        <dt>
                            <label for="Special">
                                <input id="Special" data-parent="-1" type="checkbox" value="Special"/>港澳台及海外
                            </label>
                        </dt>
                        <dd><span class="node leaf">
                          <label for="Location325">
                              <input id="Location325" data-parent="Special" type="checkbox" data-value="香港" value="325" name="province"/>香港
                          </label></span><span class="node leaf">
                          <label for="Location332">
                              <input id="Location332" data-parent="Special" type="checkbox" data-value="澳门" value="332" name="province"/>澳门
                          </label></span><span class="node leaf">
                          <label for="Location327">
                              <input id="Location327" data-parent="Special" type="checkbox" data-value="台湾" value="327" name="province"/>台湾
                          </label></span><span class="node leaf">
                          <label for="Location335">
                              <input id="Location335" data-parent="Special" type="checkbox" data-value="美国" value="335" name="province"/>美国
                          </label></span><span class="node leaf">
                          <label for="Location336">
                              <input id="Location336" data-parent="Special" type="checkbox" data-value="加拿大" value="336" name="province"/>加拿大
                          </label></span><span class="node leaf">
                          <label for="Location337">
                              <input id="Location337" data-parent="Special" type="checkbox" data-value="澳大利亚" value="337" name="province"/>澳大利亚
                          </label></span><span class="node leaf">
                          <label for="Location338">
                              <input id="Location338" data-parent="Special" type="checkbox" data-value="英国" value="338" name="province"/>英国
                          </label></span><span class="node leaf">
                          <label for="Location339">
                              <input id="Location339" data-parent="Special" type="checkbox" data-value="马来西亚" value="339" name="province"/>马来西亚
                          </label></span><span class="node leaf">
                          <label for="Location340">
                              <input id="Location340" data-parent="Special" type="checkbox" data-value="日本" value="340" name="province"/>日本
                          </label></span><span class="node leaf">
                          <label for="Location341">
                              <input id="Location341" data-parent="Special" type="checkbox" data-value="新加坡" value="341" name="province"/>新加坡
                          </label></span><span class="node leaf">
                          <label for="Location342">
                              <input id="Location342" data-parent="Special" type="checkbox" data-value="海外其他" value="342" name="province"/>海外其他
                          </label></span></dd>
                    </dl>
                </td>
            </tr>
            <tr class="node">
                <td colspan="6">
                    <dl>
                        <dt>
                            <label for="Other">
                                <input id="Other" data-parent="-1" type="checkbox" value="Other"/>其他地区
                            </label>
                        </dt>
                        <dd><span class="node leaf">
                          <label for="Location300">
                              <input id="Location300" data-parent="Other" type="checkbox" data-value="其他地区" value="300" name="province"/>其他地区
                          </label></span></dd>
                    </dl>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="clearfix opt opt-foot">
            <label for="AllProvince" class="fll">
                <input id="AllProvince" type="checkbox" value="-1"/>全选
            </label><span class="flr"><span class="btn-add">添加</span></span>
        </p>
    </div>
</div>

<div class="alertBox modCommendBox modCommendBox_JS myHide">
    <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="closeBox">X</a></div>
    <div class="alertBoxBody">
        <div class="alertBoxCon myb2">
            <!--img.uploadImg(src='#{staticUrl}/images/app-name.png', alt='')-->
            <!--input.myBtn.sBtn.myShadow(type='button', value='上传图片')-->
            <div class="alertBoxConText">
                <p><b>关闭评论，将对所有正在使用该创意的计划生效。</b><br/>确认关闭评论？</p>
            </div>
        </div>
    </div>
    <div class="alertBoxFooter">
        <div class="btnConW150 centerT0">
            <div class="myBtn redBtn sBtn myShadow floatL ok_JS">确认</div>
            <div class="myBtn sBtn myShadow floatR no_JS">取消</div>
            <div class="blankBox"></div>
        </div>
    </div>
</div>
@stop


@section('data')
bee.editPlanId   = {{ $campaign_id }};
@stop

@section('js')
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/scrollto.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/placeholder.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/error.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/checkboxgroup.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/timeRange.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/throttle.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/checkboxtree.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/fansSuggest.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/fans.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/age.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/location.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/interests.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/partTime.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/autocomplete.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/priceKit.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/orderCtrl.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app//js/libs/orderEdit.js?v={{ getenv('STATIC_VERSION') }}"></script>
@stop