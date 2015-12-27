<?php namespace Services;


use Api\ObjectApi;
use Api\FeedApi;
use Api\GuardApi;
use Base\Request;
use Base\DB;
use \Models\Creative;
use \Api\AppApi;
use \Api\WeiboApi;
use \UserInfo;
use \Result;
use \LogFile;
use \Alert;
use \Type\CreativeType;
use \Type\OpLogType;
use \Type\WeiboApiType;
use Exception\HttpException;
use \Models\OpLog;
use Adinf\Dml\Factory as DmlFactory;
use \Api\MediaApi;
use Models\ObjectTags;


class CreativeServices
{

    public function getList()
    {
        $pagination = Request::getPagination();
        $result = new Result();

        $condition = Creative::where('customer_id', UserInfo::getTargetUserId())->where(
            'status',
            CreativeType::COMMON
        );

        $keywords = Request::input("keywords");
        $column = Request::input("column");
        $app_id = Request::input("app_id");
        $disable_comment = Request::input("disable_comment");
        $audit_status = Request::input("audit_status");

        if (isset($keywords)) {
            $condition = $condition->where(
                function ($query) use ($keywords) {
                    $query->where('name', 'like', "%$keywords%")
                        ->orWhere('content', 'like', "%$keywords%");
                }
            );
        }
        if (isset($app_id)) {
            $condition = $condition->where('app_id', $app_id);
        }

        if (isset($disable_comment)) {
            $condition = $condition->where('disable_comment', $disable_comment);
        }

        if (isset($audit_status)) {
            $condition = $condition->where('audit_status', $audit_status);
        }

        $start_time = Request::input('start_time');

        if (isset($start_time)) {
            $condition = $condition->where('created_at', '>=', $start_time . ' 00:00:00');
            $end_time = Request::input('end_time');
            if (isset($end_time)) {
                $condition = $condition->where('created_at', '<=', $end_time . ' 23:59:59');
            }
        }
        $column_array = null;
        if (isset($column)) {
            $column_array = explode(',', trim($column));
        }

        $total = $condition->count();

        $data = $condition->orderBy('created_at', 'desc')->forPage($pagination[0], $pagination[1])->get(
            $column_array
        )->toArray();

        $result->data = [
            $data
        ];

        $result->total_count = $total;

        return $result->toArray();
    }


    public function store()
    {
        $result = new Result();
        // check app_id exist
        $target_id = UserInfo::getTargetUserId();
        $app_id = Request::input('app_id');
        $summery_type = Request::input('summery_type');
        $app_list = AppApi::getApps($target_id);
        if (!array_key_exists($app_id, $app_list)) {
            throw new HttpException(422, 'app_id不存在');
        }
        $app = $app_list[$app_id];

        // check name exist
        $count = Creative::where('name', Request::input('name'))->where('customer_id', $target_id)->count();
        if (!empty($count)) {
            throw new HttpException(422, '创意名称重复');
        }
        //check creatives type, card or nine?
        $tags_for_model = array();
        $media_data = array();
        $creative_type = Request::input('type');
        if($creative_type == CreativeType::CARD){
            $object = ObjectApi::import(
                $app_id,
                current(Request::input('images')),
                Request::input('display_name'),
                AppApi::getObjectSummery($app, Request::input('summery'), $summery_type),
                $summery_type,
                UserInfo::getTargetUserId(),
                $app['type']
            );
            if (!$object) {
                throw new HttpException(500, '对象入库失败');
            }
        } else if($creative_type == CreativeType::NINE) {
            //tag info
            $objects_tags = json_decode(Request::input('tags',null, false), true);
            if(!is_array($objects_tags)){
                throw new HttpException(422, '创意失败,九宫格对象创建失败，请重试');
            }
            $media_data = json_decode(Request::input('media_data',null, false), true);
            if(!is_array($media_data)){
                throw new HttpException(422, '创意失败,九宫格对象创建失败。。，请重试');
            }
            foreach( $objects_tags as $tag_pic => &$tags_list){
                foreach($tags_list as &$tags_obj){
                    $object['object_id'][$tag_pic][] = $tags_obj['tag_object_id'];
                    $object['short_url'][$tag_pic][] = $tags_obj['short_url'];
                    $object['long_url'][$tag_pic][] = $tags_obj['long_url'];
                    unset($tags_obj['tag_object_id'],$tags_obj['short_url'],$tags_obj['long_url']);
                }
            }
            $object['object_id'] = json_encode($object['object_id']);
            $object['short_url'] = json_encode($object['short_url']);
            $object['long_url'] = json_encode($object['long_url']);
            $tags_for_model = $objects_tags;
        } else {
            throw new HttpException(422, '创意失败，请重试');
        }


        // create
        DB::beginTransaction();
        $creative = new Creative();
        $creative->app_id = $app_id;
        $creative->name = Request::input('name');
        $creative->object_id = $object['object_id'];
        $creative->short_url = $object['short_url'];
        $creative->long_url = $object['long_url'];
        $creative->tags = json_encode($tags_for_model);
        $creative->operator_id = UserInfo::getCurrentUserId();
        $creative->customer_id = $target_id;
        $creative->display_name = Request::input('display_name');
        $creative->content = Request::input('content');
        $disable_comment = Request::input('disable_comment');
        $creative->images = json_encode(Request::input('images'));
        $creative->summery_type = $summery_type;
        $creative->summery = AppApi::getSummery($app, Request::input('summery'), $summery_type);
        $creative->app_type = $app['type'];

        // 发微博
        //card weibo and nine weibo?
        if($creative_type == CreativeType::CARD){
            $weibo_result = $this->weiboPublish(
                UserInfo::getTargetUserId(),
                $creative->content . ' ' . $creative->short_url,
                null,
                null
            );
        } else if($creative_type == CreativeType::NINE) {
            $weibo_result = $this->weiboPublish(
                UserInfo::getTargetUserId(),
                $creative->content,
                implode(',', array_keys($media_data)),
                json_encode($media_data)
            );
        } else {
            throw new HttpException(422, '创意失败，请重试');
        }
        $creative->mid = $weibo_result['mid'];
        FeedApi::add($creative->mid);

        if ($disable_comment) {
            $weibo_result = WeiboApi::forbidCommentByMid($creative->mid);
            if ($weibo_result) {
                $creative->disable_comment = CreativeType::DISABLE_COMMENT;
            } else {
                $creative->disable_comment = CreativeType::ENABLE_COMMENT;
                \LogFile::alert("禁止评论失败", [UserInfo::getTargetUserId(), $creative->mid]);
            }
        }

        $creative->save();

        OpLog::write(
            OpLogType::TARGET_TYPE_CREATIVE,
            $creative->id,
            $creative->name,
            OpLogType::CONTENT_TYPE_CREATIVE_CREATE,
            '',
            Request::all()
        );

        DB::commit();
        if (!getenv('DEBUG')) {
            GuardApi::send($creative, $app['name']);
        }
        $result->code = 201;
        $result->id = $creative->id;
        $result->direct_url = '/app/creatives?customer_id='. UserInfo::getTargetUserId();
        $result->message = "计划创建成功";

        return $result->toArray();
    }

    /**
     * 发微博
     *
     * @param $customer_id
     * @param $content
     * @param $pic_id
     * @param $pic_tags
     * @return array|bool|mixed
     * @throws HttpException
     */
    private function weiboPublish($customer_id, $content, $pic_id, $pic_tags){
        $weibo_result = WeiboApi::publishStatus(
            $customer_id,
            $content,
            $pic_id,
            $pic_tags
        );
        if (isset($weibo_result['error_code'])) {
            $msg = WeiboApiType::getString($weibo_result['error_code']);
            \LogFile::error("发微博失败", [UserInfo::getTargetUserId(), $weibo_result['error_code']]);
            if($msg){
                throw new HttpException(500, '系统错误，发微博失败：' . $msg);
            } else {
                throw new HttpException(500, '系统错误，发微博失败。');
            }
        }
        return $weibo_result;
    }

    public function delete()
    {
        $result = new Result();
        $creative = Creative::where('id', Request::input('id'))->where('customer_id', UserInfo::getTargetUserId())
            ->where('status', '<>', CreativeType::DELETE)->first();
        if (empty($creative)) {
            throw new HttpException(404, '创意id不存在');
        }

        $creative->status = CreativeType::DELETE;
        $creative->operator_id = UserInfo::getCurrentUserId();

        DB::beginTransaction();
        foreach ($creative->campaigns as $campaign) {
            $old_campaign = clone $campaign;
            $lock = $this->lock($campaign->id);
            if (empty($lock)) {
                continue;
            }

            $campaign->version += 1;
            $campaign->status = \Type\CampaignType::STOP_STATUS;
            $campaign->stop_type= \Type\CampaignPauseType::CAMPAIGN_NOT_EXIST_CONTENT_STATUS;
            if ($old_campaign->status != \Type\CampaignType::DRAFT_STATUS) {
                $result = \Api\EngineApi::stop($campaign->id, $campaign->getDetail());
                if (!$result) {
                    LogFile::alert('feed 下线通知引擎失败', "$creative->mid $campaign->id");
                    $this->unlock($lock);
                    throw new HttpException(500, '计划下线失败');
                }
            }

            OpLog::write(
                OpLogType::TARGET_TYPE_CAMPAIGN,
                $campaign->id,
                $campaign->name,
                OpLogType::CONTENT_TYPE_CAMPAIGN_DELETE,
                '',
                ''
            );
            $campaign->save();
            $this->unlock($lock);
            LogFile::info('feed status offline campaign success', $campaign->id);
        }
        FeedApi::delete($creative->mid);

        $creative->status = CreativeType::DELETE;
        $creative->save();

        $result->code = 200;
        $result->id = $creative->id;
        $result->message = "创意删除成功";

        OpLog::write(
            OpLogType::TARGET_TYPE_CREATIVE,
            $creative->id,
            $creative->name,
            OpLogType::CONTENT_TYPE_CREATIVE_DELETE,
            '',
            ''
        );
        DB::commit();

        return $result->toArray();
    }

    public function update()
    {
        $result = new Result();
        $creative = Creative::where('id', Request::input('id'))->where('customer_id', UserInfo::getTargetUserId())
            ->where('status', CreativeType::COMMON)->first();

        if (empty($creative)) {
            throw new HttpException(404, '创意id不存在');
        }

        // 检测mid状态
        $weibo_result = WeiboApi::getStatusInfoByMid($creative->mid);
        if ($weibo_result == false) {
            throw new HttpException(422, '创意微博内容异常');
        }

        $disable_comment = Request::input('disable_comment');
        // @todo 这里就是反的
        $disable_comment = !empty($disable_comment) ? CreativeType::DISABLE_COMMENT : CreativeType::ENABLE_COMMENT;

        // no change
        if ($creative->disable_comment != $disable_comment) {
            if (!empty($creative->mid)) {
                // 调用微博API等
                if ($disable_comment == CreativeType::DISABLE_COMMENT && $disable_comment != $creative->disable_comment) {
                    $weibo_result = WeiboApi::forbidCommentByMid($creative->mid);
                    if ($weibo_result) {
                        $creative->disable_comment = CreativeType::DISABLE_COMMENT;
                    } else {
                        LogFile::alert("禁止评论失败", [UserInfo::getTargetUserId(), $creative->mid]);
                        throw new HttpException(500, '禁止评论失败');
                    }
                }
                if ($disable_comment == CreativeType::ENABLE_COMMENT && $disable_comment != $creative->disable_comment) {
                    $weibo_result = WeiboApi::allowCommentByMid($creative->mid);
                    if ($weibo_result) {
                        $creative->disable_comment = CreativeType::ENABLE_COMMENT;
                    } else {
                        LogFile::alert("允许评论失败", [UserInfo::getTargetUserId(), $creative->mid]);
                        throw new HttpException(500, '允许评论失败');
                    }
                }
                $creative->save();
                OpLog::write(
                    OpLogType::TARGET_TYPE_CREATIVE,
                    $creative->id,
                    $creative->name,
                    OpLogType::CONTENT_TYPE_CREATIVE_COMMENT,
                    $disable_comment == 0 ? '关闭' : '开启',
                    $disable_comment == 0 ? '开启' : '关闭'
                );
            }
        }

        $result->code = 200;
        $result->id = $creative->id;
        $result->message = "计划更新成功";

        return $result->toArray();
    }

    private function lock($id)
    {
        $lock = DmlFactory::trylock(DmlFactory::REDLOCK, "campaign_{$id}", 30000, 30000);
        if (!$lock) {
            LogFile::error('feed 下线锁获取失败', 'feed 下线锁获取失败 id = '.$id);
        }
        return $lock;
    }


    private function unlock($lock)
    {
        DmlFactory::unlock($lock);
    }

    /**
     *  build tags for creatives
     * @author haicheng
     */
    public function buildTags(){

        $target_id = UserInfo::getTargetUserId();
        $photo_url = Request::input('photo_url', null);
        $photo_tags = Request::input('photo_tags', null, false);
        $tag_name = Request::input('tag_desc', null, false);
        $app_id = Request::input('app_id', null);
        $type = Request::input('type', null);

        //todo 删除
        $target_id = '5014007274';

        //validate rule
        //preg picid from $photo_url
        //make sure app is actived
        if(
            is_null($target_id) ||
            is_null($photo_url) ||
            is_null($photo_tags) ||
            is_null($tag_name) ||
            is_null($app_id) ||
            is_null($type)
        ){
            throw new HttpException(422, '参数错误了，请重试');
        }
        $photo_tags = json_decode($photo_tags, true);
        $tag_name = json_decode($tag_name, true);
        if(!is_array($photo_tags) || !is_array($tag_name) ){
            throw new HttpException(422, '图片或标签不合法，请重试');
        }
        if (count($photo_tags) < 1 || (count($photo_tags) != count($tag_name))) {
            throw new HttpException(422, '图片或标签不合法，请重试');
        }
        $creative_config = \Config::get('creative');
        preg_match($creative_config['preg_rule'],$photo_url[0],$match_pic_id);
        if(count($match_pic_id) !== 2){
            throw new HttpException(422, '图片上传失败，请重试');
        }
        $pic_id = $match_pic_id[1];
 //       $app_list = AppApi::getApps($target_id);
//        if (!array_key_exists($app_id, $app_list)) {
//            throw new HttpException(422, 'app_id不存在');
//        }

        //build a object for tag.
        //build tag with object id from media.
        //validate tags
        $pic_tags[$pic_id] = array();
        $result_data = array();
        foreach ($photo_tags as $index => $tag) {
            $object_id_for_tag = $this->getObjectId($target_id, $app_id, $tag_name[$index],$type);
            $pic_tags[$pic_id][] = array(
                'tag' => $tag_name,
                'tag_type' => $creative_config['media']['tag_type'],
                'tag_object_id' => $object_id_for_tag['object_id'],
                'pos' => $tag,
                'dir' => $creative_config['media']['dir'],
            );
            $result_data['tags'][$pic_id][] = array(
                'tag' => $tag_name,
                'tag_type' => $creative_config['media']['tag_type'],
                'tag_object_id' => $object_id_for_tag['object_id'],
                'pos' => $tag,
                'dir' => $creative_config['media']['dir'],
                'short_url' => $object_id_for_tag['short_url'],
                'long_url' => $object_id_for_tag['long_url'],
            );
        }

        LogFile::debug('debug ---> pic_tags', var_export($pic_tags, true));
        $media_result = MediaApi::build_tags($target_id, json_encode($pic_tags));
        if ($media_result === false) {
            throw new HttpException(422, '图片上传失败，请重试');
        }
        $result_data['media_data'] = $media_result['data'];

        //format return
        $result = new Result();
        $result->code = 200;
        $result->message = '成功';
        $result->data = $result_data;
        return $result->toArray();
    }

    /**
     * 获取objectid
     * @param $customer_id
     * @param $app_id
     * @param $display_name
     * @param $type
     * @return string
     * @throws HttpException
     */
    private function getObjectId($customer_id, $app_id, $display_name,$type){
        $object_tags = ObjectTags::where('customer_id', $customer_id)
            ->where('app_id', $app_id)
            ->where('display_name', $display_name)
            ->where('is_deleted', 0)->first();

        if($object_tags){
            $result_tag = array(
                'object_id' => $object_tags['object_id'],
                'short_url' => $object_tags['short_url'],
                'long_url' => urlencode($object_tags['long_url']),
            );
        }else{
            $result = ObjectApi::importTag($display_name,$app_id, $customer_id,$type);
            if(empty($result) || !isset($result['object_id'])|| !isset($result['short_url'])){
                throw new HttpException(422, '图片上传失败：标签创建失败，请重试');
            }
            $object_tags = new ObjectTags();
            $object_tags->customer_id = $customer_id;
            $object_tags->app_id = $app_id;
            $object_tags->display_name = $display_name;
            $object_tags->object_id = $result['object_id'];
            $object_tags->short_url = $result['short_url'];
            $object_tags->long_url = $result['long_url'];
            $object_tags->save();

            $result_tag = array(
                'object_id' => $result['object_id'],
                'short_url' => $result['short_url'],
                'long_url' => urlencode($object_tags['long_url']),
            );
        }
        return $result_tag;
    }
}
