<?php
$raw_code_msg = <<<HERE_DOC
19.02.20.01-fix_show_batch_error
19.02.18.01-fix_subordinate_uid_lost
19.02.13.01-uve_standard_action
19.01.31.01-fix_uve_bootad_video_check2
19.01.24.01-fix_uve_bootad_video_check
19.01.22.01-fix_uve_cpd_bootad_allowinteraction
19.01.21.01-feature_contract_delivery_type
19.01.07.01-uve_boot_ad_cpd_publish
18.11.21.01-fix_uve_repeat_campaign_id
18.11.14.01-fix_uve_connect_timeout
18.11.12-publish_modify_get_feed_list
18.11.12-publish_modify_disable_mid
18.11.12-publish_new_video_feed
18.11.05.01-the_second_phase_25
18.10.08-publish_fix_hand_start_campaign
18.09.29-publish_uve_client_Boot_ad
18.09.25-publish_2.3_phase
18.09.17-fix_user_child_id_list_publish
18.09.03-publish_modify_media_name
18.08.29.01-the_second_phase
18.07.30.01-modify_import_contract_customer_id
18.07.24.01-fix_lock_redis_config
18.07.20.02-modify_campaign_lock_optimization
18.07.20.01-modify_campaign_lock_optimization
18.07.19-modify_cpd_creative_template
18.07.17-publish_modify_cpd_url
18.07.17-publish_modify_import_contract
18.07.16.01-modify_command
18.07.11-publish_test
18.07.09.03-modify_config
18.07.09.02-ka_publish
HERE_DOC;

$code_msg_lines = explode("\n", $raw_code_msg);

foreach ($code_msg_lines as $line) {

    $date = '20' . str_replace('.', '-', substr($line, 0 , 8));

//    list(, $title) = explode('-', $line);

   echo "### [$line](http://git.intra.weibo.com/ad/bp/ka/ka-dm/tree/{$line}) ($date) \r\n";
}

