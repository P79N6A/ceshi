@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="planData" class="pageName"></div>
    <div class="adShowTable">
        <div class="adShowTableSearch">
            <label for="">时间：</label>
            <input id="startDate" type="text" name="date-from" class="adInputInput">
            <label for="search-date-to">到</label>
            <input id="endDate" type="text" name="date-to" class="adInputInput">
            <label for="" class="adShowTableSearchKeyword">关键字：</label>
            <input type="text" class="adInputInput search_key_JS">
            <input type="submit" value="搜索" class="adShowTableSearchBtn search_JS">
        </div>
        <div class="adShowTableShow">
            <div class="adShowTableHeader clearfix">
                <div class="adShowTablePlan floatL ML20"><b class="FS14">计划数据</b></div>
                <div class="floatR MR20">
                    <div class="miniBtn MT12"><a href="http://suchong.fst.weibo.com/app/campaign-reports/download?v=1439533051466&amp;_is_ajax=1&amp;customer_id=undefined&amp;per_page=15&amp;page=1" class="export_JS">

                            导出数据</a></div>
                </div>
            </div>
            <div class="adShowTableCell adShowTableCellWidth adShowTableCellHeight">
                <table>
                    <tbody><tr class="adShowTableTitle">
                        <th class="adShowTablePlan">计划ID</th>
                        <th class="adShowTableTime">计划名称</th>
                        <th class="adShowTableDaycost">推广应用</th>
                        <th class="adShowTableTotalcost">日期</th>
                        <th class="adShowTableStatus">消耗/元</th>
                        <th class="adShowTableStatus">曝光量</th>
                        <th class="adShowTablePerform">互动量<b title="互动量为有效互动量，包含图文区点击、下载区点击、短链点击、转发、关注、赞、收藏的总量，不包含评论量" class="tips_hover_JS">i</b></th>
                        <th class="adShowTablePerform">互动率<b title="互动率，为曝光中的互动情况比率" class="tips_hover_JS">i</b></th>
                        <!-- <th class="adShowTablePerform">点击量</th> -->
                        <th class='adShowTablePerform'>图文区点击量</th>  
                        <th class='adShowTablePerform'>下载区点击量</th>
                        <th class='adShowTablePerform'>短链点击量</th>
                        <th class="adShowTablePerform">转发量</th>
                        <th class="adShowTablePerform">关注量</th>
                        <th class="adShowTablePerform">赞量</th>
                        <th class="adShowTablePerform">收藏量</th>
                        <th class="adShowTablePerform">评论量</th>
                        <th class="adShowTablePerform">
                            激活量<b title="已经在第三方添加了应用监控，并在应用家填写了监控地址，系统将自动统计应用激活量。目前已支持<a href=&quot;https://www.talkingdata.com/product-market.jsp?languagetype=zh_cn&quot;  target = &quot;_blank&quot;>TalkingData</a>和<a href=&quot;http://www.admaster.com.cn&quot;  target = &quot;_blank&quot;>AdMaster</a>转化大师的监控地址" class="tips_hover_JS">i</b>
                        </th>
                        <th class="adShowTablePerform">千次曝光成本<b title="千次曝光成本，为每千次曝光的花费" class="tips_hover_JS">i</b></th>
                        <th class="adShowTablePerform">单次互动成本<b title="单次互动成本，为每一次互动行为的花费" class="tips_hover_JS">i</b></th>
                        <th class="adShowTablePerform">激活成本</th>
                    </tr>
                    <tr>
                        <td colspan="3"><img src="http://e.sinajs.cn/bpbid/images/common/loading.gif">请稍等,正在加载......    </td>
                    </tr>
                    </tbody></table>
            </div>
            <div id="Pagination" class="pagination floatR MT20">     </div>
        </div>
    </div>
</div>
@stop