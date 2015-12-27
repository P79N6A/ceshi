<ul>
    <li>
        <em class=""><i class="arrow"></i><i class="icon icon_ad8"></i><b>博文推广</b></em>
        <p>
            <a href="/bid/data?customer_id={{ UserInfo::getTargetUserId() }}">总体数据</a>
        </p>
        <p>
            <a href="/bid/data/group?customer_id={{ UserInfo::getTargetUserId() }}">广告组数据</a>
        </p>
        <p>
            <a href="/bid/data/order?customer_id={{ UserInfo::getTargetUserId() }}">广告计划数据</a>
        </p>
        <p>
            <a href="/bid/data/feed?customer_id={{ UserInfo::getTargetUserId() }}">微博创意数据</a>
        </p>
        <p>
            <a href="javascript:void(0)">人群定向数据 <i class="arr_orange_dn"></i></a>
        </p>
        <ul class="left_nav_3rd_menu" node-type="people">
            <li class=""><a href="/bid/data/age?customer_id={{ UserInfo::getTargetUserId() }}">年龄分析</a></li>
            <li class=""><a href="/bid/data/gender?customer_id={{ UserInfo::getTargetUserId() }}">性别分析</a></li>
            <li class=""><a href="/bid/data/area?customer_id={{ UserInfo::getTargetUserId() }}">地域分析</a></li>
            <li class=""><a href="/bid/data/fans?customer_id={{ UserInfo::getTargetUserId() }}">指定账号分析 </a></li>
            <li class=""><a href="/bid/data/time?customer_id={{ UserInfo::getTargetUserId() }}">分时分析</a></li>
        </ul>
    </li>
    <li>
        <em><i class="arrow"></i><i class="icon icon_ad9"></i><b>应用推广</b></em>
        <p class="">
            <a href="/card/appdata/customer/total?customer_id={{ UserInfo::getTargetUserId() }}">总体数据</a>
        </p>
        <p class="">
            <a href="/card/appdata/campaign/total?customer_id={{ UserInfo::getTargetUserId() }}">广告计划数据</a>
        </p>
        <p class="">
            <a href="/card/appdata/app/total?customer_id={{ UserInfo::getTargetUserId() }}">应用推广数据</a>
        </p>
        <p>
            <a href="/card/appdata/person/total?customer_id={{ UserInfo::getTargetUserId() }}">人群定向数据</a>
        </p>
    </li>
    <li>
        <em class=""><i class="arrow"></i><i class="icon icon_ad11"></i><b>账号推广</b></em>
        <p class="">
            <a href="/promotedaccounts/data/total?customer_id={{ UserInfo::getTargetUserId() }}">总体数据</a>
        </p>
        <p class="cur">
            <a href="/promotedaccounts/data/campaign?customer_id={{ UserInfo::getTargetUserId() }}">广告计划数据</a>
        </p>
        <p class="">
            <a href="/promotedaccounts/data/account?customer_id={{ UserInfo::getTargetUserId() }}">账号推广数据</a>
        </p>
        <p class="">
            <a href="/promotedaccounts/data/person?customer_id={{ UserInfo::getTargetUserId() }}">人群定向数据</a>
        </p>
        <p class="">
            <a href="/promotedaccounts/data/position?customer_id={{ UserInfo::getTargetUserId() }}">推广位置数据</a>
        </p>
    </li>
    <li class="expand hover"><em class="cur"><i class="arrow"></i><i class="icon_app"></i><b>应用家</b></em>
        <p class="{{ CSS::getCur('/app/customer-reports') }}"><a href="/app/customer-reports?customer_id={{ UserInfo::getTargetUserId() }}">总体数据</a></p>

        <p class="{{ CSS::getCur('/app/campaign-reports') }}"><a href="/app/campaign-reports?customer_id={{ UserInfo::getTargetUserId() }}">计划数据</a></p>

        <p class="{{ CSS::getCur('/app/app-reports') }}"><a href="/app/app-reports?customer_id={{ UserInfo::getTargetUserId() }}">应用数据</a></p>
    </li>
</ul>