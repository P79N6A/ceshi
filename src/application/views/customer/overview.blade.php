@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div class="appCon">
        <div page-name="productInfo" class="pageName"></div>
        <div class="mybox">
            <div class="TitleBar">
                <div class="floatL ML20"><b>整体情况</b></div><span class="floatR MR20 colorGreyDate">数据为一小时更新一次，最新更新时间:<span class="updateTime_JS"> </span></span>
                <div class="blankBox"></div>
            </div>
            <div class="myDateBox floatR MT10 MB10 MR20">
                <div action-data="today" class="myDateDiv floatL">今天</div>
                <div action-data="yesterday" class="myDateDiv floatL">昨天</div>
                <div action-data="before7days" class="myDateDiv select floatL">近7天</div>
                <div action-data="before30days" class="myDateDiv floatL">近30天  </div>
                <div class="myDateDiv2">
                    <input id="startDate" class="startDate"/>至
                    <input id="endDate" class="endDate"/>
                    <input type="submit" value="搜索" class="adShowTableSearchBtn searchButton_JS"/>
                </div>
            </div>
            <div class="myDataCheck">
                <div class="dataBox">
                    <div class="dataBoxDiv">
                        <div class="dataBoxtitle1 MT5">

                            曝光量
                        </div>
                        <div class="dataBoxtitle2 exposure_JS">-</div>
                    </div>
                    <div class="dataBoxDiv">
                        <div class="dataBoxtitle1 MT5">

                            互动量
                        </div>
                        <div class="dataBoxtitle2 Interactive_JS">-</div>
                    </div>
                    <div class="dataBoxDiv">
                        <div class="dataBoxtitle1 MT5">

                            互动率
                        </div>
                        <div class="dataBoxtitle2 InteractiveRate_JS">-</div>
                    </div>
                    <div class="dataBoxDiv">
                        <div class="dataBoxtitle1 MT5">

                            千次曝光成本（元）
                        </div>
                        <div class="dataBoxtitle2 milleCost_JS">-</div>
                    </div>
                    <div class="dataBoxDiv">
                        <div class="dataBoxtitle1 MT5">

                            单次互动成本（元）
                        </div>
                        <div class="dataBoxtitle2 perCost_JS">-</div>
                    </div>
                    <div class="dataBoxDiv noline">
                        <div class="dataBoxtitle1 MT5">

                            消耗（元）
                        </div>
                        <div class="dataBoxtitle2 cost_JS">-</div>
                    </div>
                    <div class="blankBox"></div>
                </div>
            </div>
            <div class="myDataCheck">
                <select class="floatL ML20 leftSelect_JS">
                    <option value="consume" selected="selected">

                        消耗(元)
                    </option>
                    <option value="pv">曝光量</option>
                    <option value="iv">

                        互动量
                    </option>
                    <option value="iv_rate">

                        互动率
                    </option>
                    <!-- <option value="click">

                        点击量
                    </option> -->
                    <option value="click_img_cnt">

                        图文区点击量
                    </option>
                    <option value="click_button_cnt">

                        下载区点击量
                    </option>
                    <option value="shorturl_clked_cnt">

                        短链点击量
                    </option>
                    
                    <option value="follow">

                        关注量
                    </option>
                    <option value="forward">

                        转发量
                    </option>
                    <option value="comment">

                        评论量
                    </option>
                    <option value="like">

                        赞量
                    </option>
                    <option value="favorite">

                        收藏量
                    </option>
                    <option value="pv_cost">

                        千次曝光成本
                    </option>
                    <option value="iv_cost">

                        单次互动成本
                    </option>
                </select>
                <select class="floatR MR20 rightSelect_JS">
                    <option value="consume">

                        消耗(元)
                    </option>
                    <option value="pv" selected="selected">曝光量</option>
                    <option value="iv">

                        互动量
                    </option>
                    <option value="iv_rate">

                        互动率
                    </option>
                    <!-- <option value="click">

                        点击量
                    </option> -->
                    <option value="click_img_cnt">

                        图文区点击量
                    </option>
                    <option value="click_button_cnt">

                        下载区点击量
                    </option>
                    <option value="shorturl_clked_cnt">

                        短链点击量
                    </option>
                    
                    <option value="follow">

                        关注量
                    </option>
                    <option value="forward">

                        转发量
                    </option>
                    <option value="comment">

                        评论量
                    </option>
                    <option value="like">

                        赞量
                    </option>
                    <option value="favorite">

                        收藏量
                    </option>
                    <option value="pv_cost">

                        千次曝光成本
                    </option>
                    <option value="iv_cost">

                        单次互动成本
                    </option>
                </select>
            </div>
            <div class="blankBox"></div>
            <div id="container"></div>
        </div>
    </div>
</div>
@stop