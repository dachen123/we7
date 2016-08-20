<?php

defined('IN_IA') or exit('Access Denied');
class Jy_pppModuleSite extends WeModuleSite{
    public function __construct(){
        load() -> classs('wesession');
        global $_W, $_GPC;
        if($_SERVER['PHP_SELF'] != '/web/index.php'){
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                WeSession :: $expire = 10800;
                $weid = $_GPC['i'];
            }else{
                WeSession :: $expire = 360000;
                $weid = $_W['uniacid'];
            }
        }else{
            $weid = $_W['uniacid'];
        }
        if (empty($weid)){
            $weid = $_W['account']['uniacid'];
        }
        $now = time();
        load() -> func('communication');
        $access_token = WeAccount :: token();
        $mid_arr = pdo_fetchall("SELECT a.mid,b.from_user FROM " . tablename('jy_ppp_xinxi_temp') . " as a left join " . tablename('jy_ppp_member') . " as b on a.mid=b.id WHERE a.weid=" . $weid . " AND a.createtime<=" . $now . " AND b.type!=3 AND b.status=1 GROUP BY a.mid ");
        foreach ($mid_arr as $key => $value2){
            if(empty($value2['from_user'])){
                continue;
            }
            $weidu = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $value2['mid'] . " AND yuedu=0 ORDER BY createtime DESC ");
            $xinxi_temp = pdo_fetchall("SELECT a.*,b.avatar,b.nicheng FROM " . tablename('jy_ppp_xinxi_temp') . " as a left join " . tablename('jy_ppp_member') . " as b on a.sendid=b.id WHERE a.weid=" . $weid . " AND a.mid=" . $value2['mid'] . " AND a.createtime<=" . $now . " GROUP BY a.mid ");
            if(count($xinxi_temp) < 7){
                $ran = array_rand($xinxi_temp);
                $xinxi_yh = $xinxi_temp[$ran];
                $title2 = "附近的'" . $xinxi_yh['nicheng'] . "'给你发了一封表白信";
                $title = urlencode($title2);
                $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('chat', array('id' => $xinxi_yh['sendid'])), 2) . "jy_ppp";
                $url = urlencode($url2);
                $image = $this -> mailui();
                $description2 = "点击下方【信箱】按钮查看所有来信";
                $description = urlencode($description2);
                $data = array("touser" => $value2['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $title, "description" => $description, "url" => $url, 'picurl' => $image)))));
                $url3 = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                $response = ihttp_request($url3, urldecode(json_encode($data)));
                $errcode = json_decode($response['content'], true);
                $data3 = array('weid' => $weid, 'type' => 'news', 'content' => $title2, 'desc' => '', 'picurl' => '', 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                $data3['mid'] = $value2['mid'];
                pdo_insert("jy_ppp_kefu", $data3);
            }else{
                $news_arr = array();
                $news_arr['articles'] = array();
                $xinxi_temp_all_num = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('jy_ppp_xinxi_temp') . " as a left join " . tablename('jy_ppp_member') . " as b on a.sendid=b.id WHERE a.weid=" . $weid . " AND a.mid=" . $value2['mid'] . " AND a.createtime<=" . $now);
                $news_list_num = $xinxi_temp_all_num + $weidu;
                $title2 = "你收到" . $news_list_num . "封表白信，请点击查看";
                $title = urlencode($title2);
                $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2) . "jy_ppp";
                $url = urlencode($url2);
                $image = $this -> mailui();
                $temp_arr = array("title" => $title, "url" => $url, 'picurl' => $image);
                array_push($news_arr['articles'], $temp_arr);
                $xinxi_temp_id = array_rand($xinxi_temp, 7);
                foreach ($xinxi_temp_id as $key => $value){
                    $value = $xinxi_temp[$value];
                    $image = tomedia($value['avatar']);
                    $text2 = $value['content'];
                    $text = urlencode($text2);
                    $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('chat', array('id' => $value['sendid'])), 2) . "jy_ppp";
                    $url = urlencode($url2);
                    $temp_arr = array("title" => $text, "url" => $url, 'picurl' => $image);
                    array_push($news_arr['articles'], $temp_arr);
                }
                $data2 = array("touser" => $value2['from_user'], "msgtype" => "news", "news" => $news_arr);
                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                $response = ihttp_request($url, urldecode(json_encode($data2)));
                $errcode = json_decode($response['content'], true);
                $data3 = array('weid' => $weid, 'type' => 'news', 'content' => $title2, 'desc' => '', 'picurl' => '', 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                $data3['mid'] = $value2['mid'];
                pdo_insert("jy_ppp_kefu", $data3);
            }
        }
        $xinxi = pdo_fetchall("SELECT a.* FROM " . tablename('jy_ppp_xinxi_temp') . " as a left join " . tablename('jy_ppp_member') . " as b on a.mid=b.id WHERE a.weid=" . $weid . " AND a.createtime<=" . $now . " AND b.type!=3 AND b.status=1 ");
        foreach ($xinxi as $key => $value){
            $data = array('weid' => $value['weid'], 'mid' => $value['mid'], 'sendid' => $value['sendid'], 'content' => $value['content'], 'type' => $value['type'], 'leixing' => $value['leixing'], 'media_id' => $value['media_id'], 'picurl' => $value['picurl'], 'createtime' => $value['createtime'],);
            pdo_insert("jy_ppp_xinxi", $data);
            pdo_delete("jy_ppp_xinxi_temp", array('id' => $value['id']));
        }
    }
    public function Post($curlPost, $url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml = $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match($reg, $subxml)){
                    $arr[$key] = $this -> xml_to_array($subxml);
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }
    public function isMobile(){
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
            return true;
        }
        if (isset ($_SERVER['HTTP_VIA'])){
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        if (isset ($_SERVER['HTTP_USER_AGENT'])){
            $clientkeywords = array ('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
                return true;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])){
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
                return true;
            }
        }
        return false;
    }
    public function doMobileZhaohu(){
        global $_W, $_GPC;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
            $weixin = 0;
            $weid = $_GPC['i'];
            $mid = $_SESSION['mid'];
            if(!empty($mid)){
                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
            }
        }else{
            $weixin = 1;
            $weid = $_W['uniacid'];
            $from_user = $_SESSION['jy_openid'];
            if(empty($from_user)){
                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'zhaohu', 'foo' => $_GPC['foo'])) . "';
				</script>";
            }else{
                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                    unset($uid);
                }else{
                    $uid = $member_temp['uid'];
                    unset($member_temp);
                }
                if(empty($uid)){
                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'zhaohu', 'foo' => $_GPC['foo'])) . "';
					</script>";
                }else{
                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                    $mid = $member['id'];
                }
            }
        }
        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
        if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
            $this -> unzhuce();
            $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
            $mid = $member['id'];
        }
        include "../addons/jy_ppp/inc/mobile/area.php";
        if(!empty($sitem['moni'])){
            if(empty($_SESSION['address']) || $_SESSION['address_time'] < time()){
                $op = $_GPC['op'];
                $op_foo = 'mail';
                $moni = 1;
                if($weixin == 1){
                    include $this -> template('addtest');
                    exit;
                }
            }else{
                $moni_province = $_SESSION['moni_province'];
                $moni_city = $_SESSION['moni_city'];
                if(empty($sitem['user_addr'])){
                    $moni_province .= $moni_city;
                }elseif ($sitem['user_addr'] == 2){
                    $moni_province = $moni_city;
                }
                if(empty($moni_province)){
                    if(empty($member['province'])){
                        $member['province'] = 11;
                    }
                    $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                    if(empty($sitem['user_addr'])){
                        $moni_province = $province[$member['province']];
                        if(!empty($member['city'])){
                            $moni_province .= $sub_array[$member['province']][$member['city']];
                        }
                    }elseif ($sitem['user_addr'] == 2){
                        if(!empty($member['city'])){
                            $moni_province = $sub_array[$member['province']][$member['city']];
                        }else{
                            $moni_province = $province[$member['province']];
                        }
                    }else{
                        $moni_province = $province[$member['province']];
                    }
                }
            }
        }
        if(!empty($member['id'])){
            $op = $_GPC['op'];
            $foo = $_GPC['foo'];
            $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
            $match_age = array('0' => '不限', '1' => '18-25岁', '2' => '26-35岁', '3' => '36-45岁', '4' => '46-55岁', '5' => '55岁以上');
            $match_height = array('0' => '不限', '1' => '160cm以下', '2' => '161-165cm', '3' => '166-170cm', '4' => '170以上');
            if ($op == 'zhaohu'){
                $str = $_GPC['str'];
                if(!empty($str)){
                    $str = substr($str, 0 , -1);
                }
                $zh = explode(",", $str);
                if($member['sex'] == 1){
                    $basic = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_basic') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                    $desc = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_desc') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                    $aihao = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                    $tezheng = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                    $temp_wt = array();
                    if(!empty($member['brith'])){
                        array_push($temp_wt, '1', '2', '3');
                        if(!empty($basic['height'])){
                            array_push($temp_wt, '4');
                        }
                    }
                    if(!empty($member['province'])){
                        array_push($temp_wt, '7');
                        if(!empty($aihao)){
                            array_push($temp_wt, '6');
                        }
                        if(!empty($tezheng)){
                            array_push($temp_wt, '5');
                        }
                    }
                    if(!empty($basic['blood'])){
                        array_push($temp_wt, '8');
                    }
                    if(!empty($desc['leixin'])){
                        array_push($temp_wt, '9');
                    }
                    if(!empty($aihao)){
                        array_push($temp_wt, '10');
                    }
                    if(!empty($tezheng)){
                        array_push($temp_wt, '11');
                    }
                    array_push($temp_wt, '12', '13', '14', '15', '16', '17', '18');
                    $temp_num = count($temp_wt)-1;
                }
                $temp_time = 10;
                foreach ($zh as $key => $value){
                    $temp = pdo_fetch("SELECT createtime FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND mid=" . $value . " AND type=0 ORDER BY createtime DESC LIMIT 1 ");
                    if(!empty($temp) && $temp['createtime'] > $today){
                    }else{
                        $data = array('weid' => $weid, 'mid' => $value, 'sendid' => $mid, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                        if($member['sex'] == 2){
                            $wenti = pdo_fetch("SELECT id,name FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND parentid=0 AND enabled=1 " . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_zhaohu') . " ) ORDER BY id LIMIT 1");
                            $data['content'] = $wenti['name'];
                            $data['zhaohuid'] = $wenti['id'];
                            if(empty($data['content'])){
                                $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                            }
                        }else{
                            $xiabiao = mt_rand(0, $temp_num);
                            $rand_wt = $temp_wt[$xiabiao];
                            switch ($rand_wt){
                            case 1: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,有时间可以聊会儿天么？";
                                break;
                            case 2: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,希望能够了解你，可以交个朋友吗？";
                                break;
                            case 3: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,我觉得我们挺合适的。";
                                break;
                            case 4: $data['content'] = "可以认识一下么？我生于" . date('Y', $member['brith']) . "年,身高" . $basic['height'] . "cm,我觉得我们挺合适的。";
                                break;
                            case 5: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",现居住在" . $province[$member['province']] . "，希望能够了解你，你怎么看？";
                                break;
                            case 6: $data['content'] = "嗨！我居住在" . $province[$member['province']] . "，平时喜欢" . $aihao['aihao'] . "，希望你能和你交朋友哈！";
                                break;
                            case 7: $data['content'] = "嗨！我是来自" . $province[$member['province']] . "的年轻人，希望你会记得我哈~";
                                break;
                            case 8: $data['content'] = "嗨！我是" . $basic['blood'] . "血型的,不知道你是不是也是这样的呢？";
                                break;
                            case 9: $data['content'] = "哈喽！我喜欢" . $basic['leixin'] . "的女生,不知道你是不是也是这样的呢？";
                                break;
                            case 10: $data['content'] = "哈喽！我平时很喜欢" . $aihao['aihao'] . ",不知道你是不是也是这样的呢？";
                                break;
                            case 11: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",希望能够了解你，可以交个朋友吗？";
                                break;
                            case 12: $data['content'] = "哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
                                break;
                            case 13: $data['content'] = "Hi,我觉得你很不错，有时间可以聊会儿天么？";
                                break;
                            case 14: $data['content'] = "hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
                                break;
                            case 15: $data['content'] = "hey，我觉得你很有气质，可以认识你吗？";
                                break;
                            case 16: $data['content'] = "我们好像在那里见过，可以认识你吗？";
                                break;
                            case 17: $data['content'] = "hey，我觉得你的打扮很多看，可以认识你吗？";
                                break;
                            default: $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                break;
                            }
                        }
                        pdo_insert("jy_ppp_xinxi", $data);
                        $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $value);
                        if($xuni_temp['type'] == 3){
                            $temp_time += mt_rand(10, 100);
                            $this -> sendxuni($member, $xuni_temp, $temp_time);
                        }
                    }
                }
                echo 1;
                exit;
            }else{
                $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                if($member['sex'] == 1){
                    $condition .= " AND sex=2 ";
                }else{
                    $condition .= " AND sex=1 ";
                }
                $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                if(!empty($match)){
                    if($match['province'] > 0){
                        $temp_province = $match['province'];
                    }else{
                        if(!empty($member['province'])){
                            $temp_province = $member['province'];
                        }else{
                            $temp_province = 11;
                        }
                    }
                    if(!empty($moni_province)){
                        $condition .= " AND ( a.type=3   ) ";
                    }else{
                        $condition .= " AND a.province= " . $temp_province;
                    }
                }else{
                    if(!empty($member['province'])){
                        $temp_province = $member['province'];
                    }else{
                        $temp_province = 11;
                    }
                    if(!empty($moni_province)){
                        $condition .= " AND ( a.type=3 ) ";
                    }else{
                        $condition .= " AND a.province= " . $temp_province;
                    }
                }
                $temp_tjid = pdo_fetch("SELECT id FROM " . tablename("jy_ppp_member") . " WHERE weid=" . $weid . " ORDER BY id DESC LIMIT 1 ");
                $temp_tjid = $temp_tjid['id'];
                $temp_tjid_rand = mt_rand(0, 9000);
                $temp_tjid_rand = $temp_tjid_rand / 10000.0;
                $temp_tjid = floor($temp_tjid * $temp_tjid_rand);
                $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.city,a.type as type2,a.brith,a.beizhu,a.mobile_auth,a.card_auth,b.height FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid  WHERE a.weid=" . $weid . $condition . " AND a.id >= " . $temp_tjid . "  LIMIT 15");
                if(count($tuijian) > 3){
                    $ttt = array_rand($tuijian, 3);
                    $tuijian_temp = array();
                    foreach ($ttt as $key => $value){
                        $tuijian_temp[] = $tuijian[$value];
                    }
                    $tuijian = $tuijian_temp;
                }
                if(!empty($tuijian)){
                    include $this -> template('zhaohu');
                }else{
                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl($foo) . "';
					</script>";
                }
            }
        }else{
            if($weixin == 1){
                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
            }else{
                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
            }
        }
    }
    public function unzhuce($rid = 0){
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $from_user = $_SESSION['jy_openid'];
        if(empty($from_user)){
            echo "<script>
				window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'zhuce', 'rid' => $rid)) . "';
			</script>";
        }else{
            $member_temp = pdo_fetch("SELECT a.uid,a.nickname,a.follow,b.avatar,b.gender FROM " . tablename('mc_mapping_fans') . " as a left join " . tablename('mc_members') . " as b on a.uid=b.uid WHERE a.openid='$from_user' AND a.uniacid=" . $weid);
            if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                unset($uid);
            }else{
                $uid = $member_temp['uid'];
                $nickname = $member_temp['nickname'];
                $avatar = $member_temp['avatar'];
                $sex = $member_temp['gender'];
                unset($member_temp);
            }
            if(empty($uid)){
                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('index', array('rid' => $rid)) . "';
				</script>";
            }
        }
        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
        $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "'");
        if(!empty($from_user)){
            $nicheng = $nickname;
            if(empty($sex)){
                $sex = 1;
            }
            if($sitem['address'] == 1){
                $province = $sitem['province'];
                if(empty($province)){
                    $province = 11;
                }
                $city = $sitem['city'];
                if(empty($city)){
                    $city = $province . "01";
                }
            }else{
                $IPaddress = '';
                if (isset($_SERVER)){
                    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                        $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
                    }else if (isset($_SERVER["HTTP_CLIENT_IP"])){
                        $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
                    }else{
                        $IPaddress = $_SERVER["REMOTE_ADDR"];
                    }
                }else{
                    if (getenv("HTTP_X_FORWARDED_FOR")){
                        $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
                    }else if (getenv("HTTP_CLIENT_IP")){
                        $IPaddress = getenv("HTTP_CLIENT_IP");
                    }else{
                        $IPaddress = getenv("REMOTE_ADDR");
                    }
                }
                $ip = $IPaddress;
                $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
                $ipinfo = json_decode(file_get_contents($url));
                if($ipinfo -> code == '1'){
                    $province = $sitem['province'];
                    if(empty($province)){
                        $province = 11;
                    }
                    $city = $sitem['city'];
                    if(empty($city)){
                        $city = $province . "01";
                    }
                }else{
                    $city = $ipinfo -> data -> city_id;
                    $province = substr($city, 0, 2);
                    $city = substr($city, 0, 4);
                }
            }
            if(empty($province)){
                $province = 11;
            }
            if(empty($city)){
                $city = 1101;
            }
            pdo_update('jy_ppp_member', array('status' => 0), array('weid' => $weid, 'from_user' => $from_user));
            $wechat_member_temp = pdo_fetch("SELECT id,sex FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND type=1 ");
            if(empty($wechat_member_temp)){
                $data = array('weid' => $weid, 'sex' => $sex, 'nicheng' => $nicheng, 'province' => $province, 'city' => $city, 'brith' => 662659200, 'status' => 1, 'type' => 1, 'from_user' => $from_user, 'uid' => $uid, 'createtime' => TIMESTAMP,);
                if(!empty($rid)){
                    $data['qrcode_id'] = $rid;
                }
                pdo_insert('jy_ppp_member', $data);
                $mid = pdo_insertid();
                $dianyuan = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_dianyuan') . " WHERE weid=" . $weid . " AND uid>0 AND status=1 ");
                load() -> func('communication');
                $access_token = WeAccount :: token();
                if(!empty($dianyuan)){
                    $image = $_W['siteroot'] . "addons/jy_ppp/images/notice2.png";
                    $text2 = "有新用户（" . $nicheng . "）注册！赶紧去查看处理吧~";
                    $text = urlencode($text2);
                    $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('dy_send', array('yhid' => $mid)), 2);
                    $url = urlencode($url2);
                    $description2 = "点击上方赶紧去查看处理吧";
                    $description = urlencode($description2);
                    foreach ($dianyuan as $key => $value){
                        $data = array("touser" => $value['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $description, "url" => $url, 'picurl' => $image)))));
                        $url3 = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                        $response = ihttp_request($url3, urldecode(json_encode($data)));
                        $errcode = json_decode($response['content'], true);
                        $data3 = array('weid' => $weid, 'type' => 'news', 'content' => $text2, 'desc' => $description2, 'picurl' => $image, 'url' => $url2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                        $data3['mid'] = $value['id'];
                        pdo_insert("jy_ppp_kefu", $data3);
                    }
                }
            }else{
                if($wechat_member_temp['sex'] != $sex){
                    pdo_update("jy_ppp_member", array('nicheng' => $nicheng, 'from_user' => $from_user, 'uid' => $uid, 'status' => 1), array('id' => $wechat_member_temp['id']));
                }else{
                    pdo_update("jy_ppp_member", array('sex' => $sex, 'nicheng' => $nicheng, 'from_user' => $from_user, 'uid' => $uid, 'status' => 1), array('id' => $wechat_member_temp['id']));
                }
                $mid = $wechat_member_temp['id'];
            }
            if(!empty($avatar)){
                $temp_thumb = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_thumb') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND thumb='" . $avatar . "'");
                if(empty($temp_thumb)){
                    $data2 = array('weid' => $weid, 'mid' => $mid, 'thumb' => $avatar, 'createtime' => TIMESTAMP,);
                    if(!empty($sitem['thumb'])){
                        $data2['type'] = 0;
                    }else{
                        $data2['type'] = 1;
                        pdo_update("jy_ppp_member", array('avatar' => $avatar), array('id' => $mid));
                    }
                    pdo_insert("jy_ppp_thumb", $data2);
                }
            }
        }else{
            echo "<script>
					window.location.href = '" . $this -> createMobileUrl('index', array('rid' => $rid)) . "';
				</script>";
        }
    }
    public function doMobileIndex(){
        global $_W, $_GPC;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
            $weixin = 0;
            $weid = $_GPC['i'];
            $mid = $_SESSION['mid'];
            if(!empty($mid)){
                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
            }
            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
        }else{
            $weixin = 1;
            $weid = $_W['uniacid'];
            $from_user = $_SESSION['jy_openid'];
            if(empty($from_user)){
                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'index', 'rid' => $_GPC['rid'])) . "';
				</script>";
            }else{
                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                    unset($uid);
                }else{
                    $uid = $member_temp['uid'];
                    unset($member_temp);
                }
                if(empty($uid)){
                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'index', 'rid' => $_GPC['rid'])) . "';
					</script>";
                }else{
                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                    $mid = $member['id'];
                }
            }
            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
            if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                $rid = intval($_GPC['rid']);
                if(!empty($rid)){
                    $this -> unzhuce($rid);
                }else{
                    $this -> unzhuce();
                }
                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                $mid = $member['id'];
            }
            if(!empty($sitem['moni'])){
                if(empty($_SESSION['address']) || $_SESSION['address_time'] < time()){
                    $op = $_GPC['op'];
                    $op_foo = 'index';
                    if($op == 'addtest'){
                        $latitude = $_GPC['latitude'];
                        $longitude = $_GPC['longitude'];
                        if(empty($latitude) || empty($longitude)){
                        }else{
                            $url = "http://api.map.baidu.com/geocoder/v2/?ak=" . $sitem['bd_ak'] . "&location=" . $latitude . "," . $longitude . "&output=json&pois=0";
                            load() -> func('communication');
                            $response = ihttp_get($url);
                            if(!is_error($response)){
                                $data = @json_decode($response['content'], true);
                                if(empty($data) || $data['status'] != 0){
                                }else{
                                    $data = $data['result'];
                                    $address = '';
                                    if(!empty($data['addressComponent'])){
                                        $address = $data['addressComponent'];
                                    }
                                    if(empty($address['city'])){
                                    }else{
                                        foreach ($address as $key => $value){
                                            if($key == 'province'){
                                                $moni_province = $value;
                                                $_SESSION['moni_province'] = $value;
                                            }
                                            if($key == 'city'){
                                                $moni_city = $value;
                                                $_SESSION['moni_city'] = $value;
                                            }
                                            if($key == 'district'){
                                                $moni_district = $value;
                                            }
                                        }
                                    }
                                }
                            }else{
                            }
                        }
                        $_SESSION['address'] = 1;
                        $_SESSION['address_time'] = time() + 3600 * 4;
                        echo 1;
                        exit;
                    }
                    $moni = 1;
                    include $this -> template('addtest');
                    exit;
                }else{
                    $moni_province = $_SESSION['moni_province'];
                    $moni_city = $_SESSION['moni_city'];
                    if(empty($sitem['user_addr'])){
                        $moni_province .= $moni_city;
                    }elseif ($sitem['user_addr'] == 2){
                        $moni_province = $moni_city;
                    }
                    if(empty($moni_province)){
                        if(empty($member['province'])){
                            $member['province'] = 11;
                        }
                        $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                        if(empty($sitem['user_addr'])){
                            $moni_province = $province[$member['province']];
                            if(!empty($member['city'])){
                                $moni_province .= $sub_array[$member['province']][$member['city']];
                            }
                        }elseif ($sitem['user_addr'] == 2){
                            if(!empty($member['city'])){
                                $moni_province = $sub_array[$member['province']][$member['city']];
                            }else{
                                $moni_province = $province[$member['province']];
                            }
                        }else{
                            $moni_province = $province[$member['province']];
                        }
                    }
                }
            }
        }
        if(!empty($member['id'])){
            $op = $_GPC['op'];
            $temp_log = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_login_log') . " WHERE weid=" . $weid . " AND mid=" . $mid . " ORDER BY createtime DESC LIMIT 1 ");
            $today = strtotime("today");
            if($temp_log['createtime'] < $today){
                $data = array('weid' => $weid, 'mid' => $mid, 'createtime' => TIMESTAMP,);
                pdo_insert('jy_ppp_login_log', $data);
                $this -> sendxuni($member);
                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('zhaohu', array('foo' => 'index')) . "';
				</script>";
                message($today);
                exit;
            }else{
                $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                $match_age = array('0' => '不限', '1' => '18-25岁', '2' => '26-35岁', '3' => '36-45岁', '4' => '46-55岁', '5' => '55岁以上');
                $match_height = array('0' => '不限', '1' => '160cm以下', '2' => '161-165cm', '3' => '166-170cm', '4' => '170以上');
                if($op == 'zhaohu2'){
                    $temp_thumb = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_thumb') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND type!=4");
                    if(empty($temp_thumb)){
                        echo 3;
                        exit;
                    }
                    $id = $_GPC['id'];
                    $today = strtotime("today");
                    $temp = pdo_fetch("SELECT createtime FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND mid=" . $id . " AND type=0 ORDER BY createtime DESC LIMIT 1 ");
                    if(!empty($temp) && $temp['createtime'] > $today){
                        echo 2;
                    }else{
                        $data = array('weid' => $weid, 'mid' => $id, 'sendid' => $mid, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                        if($member['sex'] == 2){
                            $wenti = pdo_fetch("SELECT id,name FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND parentid=0 AND enabled=1 " . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_zhaohu') . " ) ORDER BY id LIMIT 1");
                            $data['content'] = $wenti['name'];
                            $data['zhaohuid'] = $wenti['id'];
                            if(empty($data['content'])){
                                $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                            }
                        }else{
                            $basic = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_basic') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                            $desc = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_desc') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                            $aihao = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                            $tezheng = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                            $temp_wt = array();
                            if(!empty($member['brith'])){
                                array_push($temp_wt, '1', '2', '3');
                                if(!empty($basic['height'])){
                                    array_push($temp_wt, '4');
                                }
                            }
                            if(!empty($member['province'])){
                                array_push($temp_wt, '7');
                                if(!empty($aihao)){
                                    array_push($temp_wt, '6');
                                }
                                if(!empty($tezheng)){
                                    array_push($temp_wt, '5');
                                }
                            }
                            if(!empty($basic['blood'])){
                                array_push($temp_wt, '8');
                            }
                            if(!empty($desc['leixin'])){
                                array_push($temp_wt, '9');
                            }
                            if(!empty($aihao)){
                                array_push($temp_wt, '10');
                            }
                            if(!empty($tezheng)){
                                array_push($temp_wt, '11');
                            }
                            array_push($temp_wt, '12', '13', '14', '15', '16', '17', '18');
                            $temp_num = count($temp_wt)-1;
                            $xiabiao = mt_rand(0, $temp_num);
                            $rand_wt = $temp_wt[$xiabiao];
                            switch ($rand_wt){
                            case 1: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,有时间可以聊会儿天么？";
                                break;
                            case 2: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,希望能够了解你，可以交个朋友吗？";
                                break;
                            case 3: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,我觉得我们挺合适的。";
                                break;
                            case 4: $data['content'] = "可以认识一下么？我生于" . date('Y', $member['brith']) . "年,身高" . $basic['height'] . "cm,我觉得我们挺合适的。";
                                break;
                            case 5: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",现居住在" . $province[$member['province']] . "，希望能够了解你，你怎么看？";
                                break;
                            case 6: $data['content'] = "嗨！我居住在" . $province[$member['province']] . "，平时喜欢" . $aihao['aihao'] . "，希望你能和你交朋友哈！";
                                break;
                            case 7: $data['content'] = "嗨！我是来自" . $province[$member['province']] . "的年轻人，希望你会记得我哈~";
                                break;
                            case 8: $data['content'] = "嗨！我是" . $basic['blood'] . "血型的,不知道你是不是也是这样的呢？";
                                break;
                            case 9: $data['content'] = "哈喽！我喜欢" . $basic['leixin'] . "的女生,不知道你是不是也是这样的呢？";
                                break;
                            case 10: $data['content'] = "哈喽！我平时很喜欢" . $aihao['aihao'] . ",不知道你是不是也是这样的呢？";
                                break;
                            case 11: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",希望能够了解你，可以交个朋友吗？";
                                break;
                            case 12: $data['content'] = "哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
                                break;
                            case 13: $data['content'] = "Hi,我觉得你很不错，有时间可以聊会儿天么？";
                                break;
                            case 14: $data['content'] = "hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
                                break;
                            case 15: $data['content'] = "hey，我觉得你很有气质，可以认识你吗？";
                                break;
                            case 16: $data['content'] = "我们好像在那里见过，可以认识你吗？";
                                break;
                            case 17: $data['content'] = "hey，我觉得你的打扮很多看，可以认识你吗？";
                                break;
                            default: $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                break;
                            }
                        }
                        pdo_insert("jy_ppp_xinxi", $data);
                        $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                        if($xuni_temp['type'] == 3){
                            $this -> sendxuni($member, $xuni_temp);
                        }
                        echo 1;
                        $kefu_member = pdo_fetch("SELECT type,from_user,nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                        $send_member = pdo_fetch("SELECT nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                        if($kefu_member['type'] != 3 && !empty($kefu_member['from_user'])){
                            $send_kefu_tf = false;
                            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                            if($sitem['jiange'] > 0){
                                $temp_kefu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " ORDER BY createtime DESC LIMIT 1 ");
                                $temp_jg_time = time() - $temp_kefu['createtime'];
                                if($temp_jg_time > $sitem['jiange'] * 60){
                                    $today = strtotime('today');
                                    $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                    if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                        $send_kefu_tf = true;
                                    }
                                }
                            }else{
                                $today = strtotime('today');
                                $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                    $send_kefu_tf = true;
                                }
                            }
                            if($send_kefu_tf == true){
                                $text2 = "附近的'" . $send_member['nicheng'] . "'给你发了一封表白信";
                                $text = urlencode($text2);
                                $desc2 = "点击下方【信箱】按钮查看所有来信";
                                $desc = urlencode($desc2);
                                $pic2 = $this -> mailui();
                                $pic = urlencode($pic2);
                                $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2);
                                $url = urlencode($url2);
                                $access_token = WeAccount :: token();
                                $data = array("touser" => $kefu_member['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $desc, "url" => $url, 'picurl' => $pic)))));
                                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                                load() -> func('communication');
                                $response = ihttp_request($url, urldecode(json_encode($data)));
                                $errcode = json_decode($response['content'], true);
                                $data3 = array('weid' => $weid, 'mid' => $id, 'type' => 'news', 'content' => $text2, 'desc' => $desc2, 'url' => $url2, 'picurl' => $pic2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                                pdo_insert("jy_ppp_kefu", $data3);
                            }
                        }
                    }
                    exit;
                }elseif($op == 'more'){
                    $str = $_GPC['str'];
                    if(empty($str)){
                        echo json_encode(array('status' => 3));
                        exit;
                    }else{
                        $str = substr($str, 0, -1);
                    }
                    $condition = $condition .= " AND a.id NOT IN ( " . $str . " ) ";
                    if($member['sex'] == 1){
                        $condition .= " AND sex=2 ";
                    }else{
                        $condition .= " AND sex=1 ";
                    }
                    $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                    if(!empty($match)){
                        if($match['province'] > 0){
                            $temp_province = $match['province'];
                        }else{
                            if(!empty($member['province'])){
                                $temp_province = $member['province'];
                            }else{
                                $temp_province = 11;
                            }
                        }
                        if(!empty($moni_province)){
                            $condition .= " AND ( a.type=3 OR (a.type!=3 AND a.province= " . $temp_province . ")   ) ";
                        }else{
                            $condition .= " AND a.province= " . $temp_province;
                        }
                        if(!empty($match['height'])){
                            if($match['height'] == 1){
                                $condition .= " AND b.height<= 160 ";
                            }elseif ($match['height'] == 2){
                                $condition .= " AND b.height> 160 AND b.height<=165 ";
                            }elseif ($match['height'] == 3){
                                $condition .= " AND b.height> 165 AND b.height<=170 ";
                            }else{
                                $condition .= " AND b.height> 170 ";
                            }
                        }
                        if(!empty($match['income'])){
                            if($match['income'] == 1){
                                $condition .= " AND ( b.income= '2000-5000' OR b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
                            }elseif ($match['income'] == 2){
                                $condition .= " AND ( b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
                            }elseif ($match['income'] == 3){
                                $condition .= " AND ( b.income= '10000-20000' OR b.income= '20000以上' ) ";
                            }
                        }
                        if(!empty($match['education'])){
                            if($match['education'] == 1){
                                $condition .= " AND ( b.education= '高中及中专' OR b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
                            }elseif ($match['education'] == 2){
                                $condition .= " AND ( b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
                            }elseif ($match['income'] == 3){
                                $condition .= " AND ( b.education= '本科' OR b.education= '硕士及以上' ) ";
                            }
                        }
                        if(!empty($match['age'])){
                            $now = time();
                            $y = date('Y', $now);
                            $m = date('m', $now);
                            $d = date('d', $now);
                            if($match['age'] == 1){
                                $temp_y = $y-18;
                                $temp_y2 = $y-25;
                                $temp_y = '' . $temp_y . $m . $d;
                                $temp_y2 = '' . $temp_y2 . $m . $d;
                                $temp_brith = strtotime($temp_y);
                                $temp_brith2 = strtotime($temp_y2);
                                $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                            }elseif ($match['age'] == 2){
                                $temp_y = $y-25;
                                $temp_y2 = $y-35;
                                $temp_y = '' . $temp_y . $m . $d;
                                $temp_y2 = '' . $temp_y2 . $m . $d;
                                $temp_brith = strtotime($temp_y);
                                $temp_brith2 = strtotime($temp_y2);
                                $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                            }elseif ($match['age'] == 3){
                                $temp_y = $y-35;
                                $temp_y2 = $y-45;
                                $temp_y = '' . $temp_y . $m . $d;
                                $temp_y2 = '' . $temp_y2 . $m . $d;
                                $temp_brith = strtotime($temp_y);
                                $temp_brith2 = strtotime($temp_y2);
                                $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                            }elseif ($match['age'] == 4){
                                $temp_y = $y-45;
                                $temp_y2 = $y-55;
                                $temp_y = '' . $temp_y . $m . $d;
                                $temp_y2 = '' . $temp_y2 . $m . $d;
                                $temp_brith = strtotime($temp_y);
                                $temp_brith2 = strtotime($temp_y2);
                                $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                            }else{
                                $temp_y = $y-55;
                                $temp_y = '' . $temp_y . $m . $d;
                                $temp_brith = strtotime($temp_y);
                                $condition .= " AND ( a.brith< " . $temp_brith . " ) ";
                            }
                        }
                    }
                    if($member['sex'] == 2){
                        $xz_sex = "女生";
                    }else{
                        $xz_sex = "男生";
                    }
                    $today = strtotime("today");
                    if(empty($sitem['sql_style'])){
                        $temp_tjid = pdo_fetch("SELECT id FROM " . tablename("jy_ppp_member") . " WHERE weid=" . $weid . " ORDER BY id DESC LIMIT 1 ");
                        $temp_tjid = $temp_tjid['id'];
                        $temp_tjid_rand = mt_rand(0, 8000);
                        $temp_tjid_rand = $temp_tjid_rand / 10000.0;
                        $temp_tjid = floor($temp_tjid * $temp_tjid_rand);
                        $tuijian = pdo_fetchall("SELECT a.type,a.id,a.avatar,a.sex,a.nicheng,a.province,a.city,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . " AND a.id >= " . $temp_tjid . " LIMIT 20");
                        if(count($tuijian) > 12){
                            $ttt = array_rand($tuijian, 12);
                            $tuijian_temp = array();
                            foreach ($ttt as $key => $value){
                                $tuijian_temp[] = $tuijian[$value];
                            }
                            $tuijian = $tuijian_temp;
                        }
                    }else{
                        $tuijian = pdo_fetchall("SELECT a.type,a.id,a.avatar,a.sex,a.nicheng,a.province,a.city,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . " LIMIT 200");
                        if(count($tuijian) > 12){
                            $ttt = array_rand($tuijian, 12);
                            $tuijian_temp = array();
                            foreach ($ttt as $key => $value){
                                $tuijian_temp[] = $tuijian[$value];
                            }
                            $tuijian = $tuijian_temp;
                        }
                    }
                    $html = '';
                    if(!empty($tuijian)){
                        if(empty($sitem['indexui'])){
                            if(empty($sitem['user_addr']) || $sitem['user_addr'] == 2){
                                include "../addons/jy_ppp/inc/mobile/area.php";
                            }
                            foreach ($tuijian as $key => $l){
                                if(!empty($l['avatar'])){
                                    $avatar = tomedia($l['avatar']);
                                }else{
                                    if($l['sex'] == 1){
                                        $avatar = "../addons/jy_ppp/images/boy.png";
                                    }else{
                                        $avatar = "../addons/jy_ppp/images/girl.png";
                                    }
                                }
                                $ziliao = '';
                                if(!empty($l['brith'])){
                                    $birthday = $l['brith'];
                                    $now = time();
                                    $month = 0;
                                    if(date('m', $now) > date('m', $birthday)) $month = 1;
                                    if(date('m', $now) == date('m', $birthday)) if(date('d', $now) >= date('d', $birthday)) $month = 1;
                                        $nianlin = date('Y', $now) - date('Y', $birthday) + $month;
                                        $ziliao .= $nianlin . "岁";
                                    }
                                    if(!empty($moni_province) && $l['type'] == 3){
                                        $ziliao .= " | " . $moni_province;
                                    }else{
                                        if(empty($sitem['user_addr'])){
                                            if(!empty($l['city'])){
                                                $province[$l['province']] = $province[$l['province']] . $sub_array[$l['province']][$l['city']];
                                            }else{
                                                $province[$l['province']] = $province[$l['province']];
                                            }
                                        }else if($sitem['user_addr'] == 1){
                                            $province[$l['province']] = $province[$l['province']];
                                        }else if($sitem['user_addr'] == 2){
                                            if(!empty($l['city'])){
                                                $province[$l['province']] = $sub_array[$l['province']][$l['city']];
                                            }else{
                                                $province[$l['province']] = $province[$l['province']];
                                            }
                                        }
                                        if(!empty($l['province'])){
                                            $ziliao .= " | " . $province[$l['province']];
                                        }
                                    }
                                    $thumb = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_thumb') . " WHERE weid=" . $weid . " AND mid=" . $l['id'] . " AND ( type=1 OR type=2 ) ");
                                    $ziliao .= " | " . $thumb . "张照片";
                                    $temp_aihao = pdo_fetch("SELECT aihao FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $l['id'] . " LIMIT 1 ");
                                    $aihao = $temp_aihao['aihao'];
                                    $temp_tezheng = pdo_fetch("SELECT tezheng FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $l['id'] . " LIMIT 1 ");
                                    $tezheng = $temp_tezheng['tezheng'];
                                    if(!empty($l['beizhu'])){
                                        $match = $l['beizhu'];
                                    }else{
                                        if(!empty($moni_province) && $l['type'] == 3){
                                            $match = "寻找" . $moni_province . "的" . $xz_sex;
                                        }else{
                                            if (!empty($l['province2'])){
                                                $match = "寻找" . $province[$l['province2']];
                                                if(!empty($l['age'])){
                                                    $match .= " " . $match_age[$l['age']];
                                                }
                                                if(!empty($l['height2'])){
                                                    $match .= " " . $match_height[$l['height2']] . "cm";
                                                }
                                                $match .= "的" . $xz_sex;
                                            }else{
                                                $match = "寻找";
                                                if (!empty($l['province'])){
                                                    $match .= $province[$l['province']];
                                                }else{
                                                    $match .= $province[11];
                                                }
                                                $match .= "的" . $xz_sex;
                                            }
                                        }
                                    }
                                    $a_zh = '<a class="hello" onclick="sayHello(this,' . $l['id'] . ')" >打招呼</a>';
                                    $html .= '<div class="inflo" data-id="' . $l['id'] . '"><a href="' . $this -> createMobileUrl('detail', array('id' => $l['id'])) . '"><ul class="disbox-hor home-user-list"><li class="foot-icon"><img src="' . $avatar . '" /></li><li class="disbox-flex user_mession"><b class="tit">' . $l['nicheng'] . '</b><p>' . $ziliao . '</p><p class="feature">' . $biaoqian . '</p><p class="recite">' . $match . '</p></ul></a><div class="bot_btn"><a href="' . $this -> createMobileUrl('detail', array('id' => $l['id'])) . '" class="look">看看Ta</a><a class="hello" onclick="sayHello(this,' . $l['id'] . ')">打招呼</a></div></div>';
                                }
                            }else{
                                $lll = 0;
                                foreach ($tuijian as $key => $l){
                                    if(!empty($l['avatar'])){
                                        $avatar = tomedia($l['avatar']);
                                    }else{
                                        if($l['sex'] == 1){
                                            $avatar = "../addons/jy_ppp/images/boy.png";
                                        }else{
                                            $avatar = "../addons/jy_ppp/images/girl.png";
                                        }
                                    }
                                    $a_zh = '<span id="span_' . $l['id'] . '" onclick="sayHello(this,' . $l['id'] . ')" class="default"></span>';
                                    if($lll == 3){
                                        $html .= '<li style="margin-bottom: 5px;" class="inflo2" data-id="' . $l['id'] . '">';
                                    }else{
                                        $html .= '<li style=" margin-right: 3px;  margin-bottom: 5px;"  class="inflo2" data-id="' . $l['id'] . '">';
                                    }
                                    $html .= '<div class="mv_box"><a href="' . $this -> createMobileUrl('detail', array('id' => $l['id'])) . '"><img src="' . $avatar . '" /></a>' . $a_zh . '</div></li>';
                                    $lll = $lll + 1;
                                    if($lll == 3){
                                        $lll = 0;
                                    }
                                }
                            }
                        }
                        if(count($tuijian) < 12){
                            echo json_encode(array('status' => 2, 'log' => $html));
                            exit;
                        }else{
                            echo json_encode(array('status' => 1, 'log' => $html));
                            exit;
                        }
                    }else{
                        $menu = 1;
                        if($member['sex'] == 1){
                            $condition .= " AND sex=2 ";
                        }else{
                            $condition .= " AND sex=1 ";
                        }
                        $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                        if(!empty($match)){
                            if($match['province'] > 0){
                                $temp_province = $match['province'];
                            }else{
                                if(!empty($member['province'])){
                                    $temp_province = $member['province'];
                                }else{
                                    $temp_province = 11;
                                }
                            }
                            if(!empty($moni_province)){
                                $condition .= " AND ( a.type=3 OR (a.type!=3 AND a.province= " . $temp_province . ")   ) ";
                            }else{
                                $condition .= " AND a.province= " . $temp_province;
                            }
                            if(!empty($match['height'])){
                                if($match['height'] == 1){
                                    $condition .= " AND b.height<= 160 ";
                                }elseif ($match['height'] == 2){
                                    $condition .= " AND b.height> 160 AND b.height<=165 ";
                                }elseif ($match['height'] == 3){
                                    $condition .= " AND b.height> 165 AND b.height<=170 ";
                                }else{
                                    $condition .= " AND b.height> 170 ";
                                }
                            }
                            if(!empty($match['income'])){
                                if($match['income'] == 1){
                                    $condition .= " AND ( b.income= '2000-5000' OR b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
                                }elseif ($match['income'] == 2){
                                    $condition .= " AND ( b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
                                }elseif ($match['income'] == 3){
                                    $condition .= " AND ( b.income= '10000-20000' OR b.income= '20000以上' ) ";
                                }
                            }
                            if(!empty($match['education'])){
                                if($match['education'] == 1){
                                    $condition .= " AND ( b.education= '高中及中专' OR b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
                                }elseif ($match['education'] == 2){
                                    $condition .= " AND ( b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
                                }elseif ($match['income'] == 3){
                                    $condition .= " AND ( b.education= '本科' OR b.education= '硕士及以上' ) ";
                                }
                            }
                            if(!empty($match['age'])){
                                $now = time();
                                $y = date('Y', $now);
                                $m = date('m', $now);
                                $d = date('d', $now);
                                if($match['age'] == 1){
                                    $temp_y = $y-18;
                                    $temp_y2 = $y-25;
                                    $temp_y = '' . $temp_y . $m . $d;
                                    $temp_y2 = '' . $temp_y2 . $m . $d;
                                    $temp_brith = strtotime($temp_y);
                                    $temp_brith2 = strtotime($temp_y2);
                                    $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                                }elseif ($match['age'] == 2){
                                    $temp_y = $y-25;
                                    $temp_y2 = $y-35;
                                    $temp_y = '' . $temp_y . $m . $d;
                                    $temp_y2 = '' . $temp_y2 . $m . $d;
                                    $temp_brith = strtotime($temp_y);
                                    $temp_brith2 = strtotime($temp_y2);
                                    $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                                }elseif ($match['age'] == 3){
                                    $temp_y = $y-35;
                                    $temp_y2 = $y-45;
                                    $temp_y = '' . $temp_y . $m . $d;
                                    $temp_y2 = '' . $temp_y2 . $m . $d;
                                    $temp_brith = strtotime($temp_y);
                                    $temp_brith2 = strtotime($temp_y2);
                                    $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                                }elseif ($match['age'] == 4){
                                    $temp_y = $y-45;
                                    $temp_y2 = $y-55;
                                    $temp_y = '' . $temp_y . $m . $d;
                                    $temp_y2 = '' . $temp_y2 . $m . $d;
                                    $temp_brith = strtotime($temp_y);
                                    $temp_brith2 = strtotime($temp_y2);
                                    $condition .= " AND ( a.brith> " . $temp_brith2 . " && a.brith< " . $temp_brith . " ) ";
                                }else{
                                    $temp_y = $y-55;
                                    $temp_y = '' . $temp_y . $m . $d;
                                    $temp_brith = strtotime($temp_y);
                                    $condition .= " AND ( a.brith< " . $temp_brith . " ) ";
                                }
                            }
                        }else{
                            if(!empty($member['province'])){
                                $temp_province = $member['province'];
                            }else{
                                $temp_province = 11;
                            }
                        }
                        if($member['sex'] == 2){
                            $xz_sex = "女生";
                        }else{
                            $xz_sex = "男生";
                        }
                        if(empty($sitem['sql_style'])){
                            $temp_tjid = pdo_fetch("SELECT id FROM " . tablename("jy_ppp_member") . " WHERE weid=" . $weid . " ORDER BY id DESC LIMIT 1 ");
                            $temp_tjid = $temp_tjid['id'];
                            $temp_tjid_rand = mt_rand(0, 8000);
                            $temp_tjid_rand = $temp_tjid_rand / 10000.0;
                            $temp_tjid = floor($temp_tjid * $temp_tjid_rand);
                            $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.type,a.sex,a.nicheng,a.province,a.city,a.brith,a.beizhu,a.mobile_auth,a.card_auth,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . " AND a.id >= " . $temp_tjid . "  LIMIT 20  ");
                            if(count($tuijian) > 12){
                                $ttt = array_rand($tuijian, 12);
                                $tuijian_temp = array();
                                foreach ($ttt as $key => $value){
                                    $tuijian_temp[] = $tuijian[$value];
                                }
                                $tuijian = $tuijian_temp;
                            }
                        }else{
                            $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.type,a.sex,a.nicheng,a.province,a.city,a.brith,a.beizhu,a.mobile_auth,a.card_auth,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . "  LIMIT 100  ");
                            if(count($tuijian) > 12){
                                $ttt = array_rand($tuijian, 12);
                                $tuijian_temp = array();
                                foreach ($ttt as $key => $value){
                                    $tuijian_temp[] = $tuijian[$value];
                                }
                                $tuijian = $tuijian_temp;
                            }
                        }
                        if(empty($sitem['user_addr']) || $sitem['user_addr'] == 2){
                            include "../addons/jy_ppp/inc/mobile/area.php";
                        }
                        foreach ($tuijian as $key => $value){
                            $tuijian[$key]['thumb'] = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_thumb') . " WHERE weid=" . $weid . " AND mid=" . $value['id'] . " AND ( type=1 OR type=2 ) ");
                            $temp_aihao = pdo_fetch("SELECT aihao FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $value['id'] . " LIMIT 1 ");
                            $tuijian[$key]['aihao'] = $temp_aihao['aihao'];
                            $temp_tezheng = pdo_fetch("SELECT tezheng FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $value['id'] . " LIMIT 1 ");
                            $tuijian[$key]['tezheng'] = $temp_tezheng['tezheng'];
                            if(empty($sitem['indexui'])){
                                if(!empty($moni_province) && $value['type'] == 3){
                                    $tuijian[$key]['province'] = $moni_province;
                                }else{
                                    if(empty($sitem['user_addr'])){
                                        if(!empty($value['city'])){
                                            $tuijian[$key]['province'] = $province[$value['province']] . $sub_array[$value['province']][$value['city']];
                                        }else{
                                            $tuijian[$key]['province'] = $province[$value['province']];
                                        }
                                    }else if($sitem['user_addr'] == 1){
                                        $tuijian[$key]['province'] = $province[$value['province']];
                                    }else if($sitem['user_addr'] == 2){
                                        if(!empty($value['city'])){
                                            $tuijian[$key]['province'] = $sub_array[$value['province']][$value['city']];
                                        }else{
                                            $tuijian[$key]['province'] = $province[$value['province']];
                                        }
                                    }
                                }
                            }
                        }
                        $weidu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND yuedu=0 AND mid= " . $mid . " AND createtime<=" . time());
                        if(empty($sitem['indexui'])){
                            include $this -> template('index');
                        }else{
                            include $this -> template('index2');
                        }
                    }
                }
            }else{
                if($weixin == 1){
                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce', array('rid' => $rid)) . "';
					</script>";
                }else{
                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                }
            }
        }
        public function sendxuni($member, $xuni = '', $time = ''){
            global $_W, $_GPC;
            load() -> func('communication');
            $access_token = WeAccount :: token();
            $sex = $member['sex'];
            $mid = $member['id'];
            $weid = $_W['uniacid'];
            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
            if(empty($xuni)){
                if($sex == 1){
                    $xuni_arr = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND sex=2 AND type=3 AND status=1 ORDER BY id DESC ");
                }else{
                    $xuni_arr = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND sex=1 AND type=3 AND status=1 ORDER BY id DESC ");
                }
            }else{
                $xuni_arr[] = $xuni;
            }
            if($sex == 1){
                $sex2 = 2;
            }else{
                $sex2 = 1;
            }
            if(!empty($xuni_arr)){
                $hufu_arr = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_hf') . " WHERE weid=" . $weid . " AND enabled=1 AND sex!=" . $sex2 . " AND parentid=0 ");
                if(empty($hufu_arr)){
                    $hufu_arr = array();
                    $hufu_arr[] = array('type' => 0, 'name' => 'hello,你好哈~');
                    $hufu_arr[] = array('type' => 0, 'name' => '在干嘛呐？');
                    $hufu_arr[] = array('type' => 0, 'name' => '无聊ing，聊聊天呗');
                    $hufu_arr[] = array('type' => 0, 'name' => 'hey');
                    $hufu_arr[] = array('type' => 0, 'name' => '我刚刚看到你在线了哦~');
                    $hufu_arr[] = array('type' => 0, 'name' => '可以交个朋友吗?');
                }
                $xuni_count = count($xuni_arr);
                if($xuni_count >= $sitem['kjmsg_num']){
                    $xuni = array_rand($xuni_arr, $sitem['kjmsg_num']);
                    if($sitem['kjmsg_num'] == 1){
                        $xuni = array('0' => $xuni);
                    }
                }else{
                    if($xuni_count == 1){
                        $xuni = array();
                        $xuni[] = 0;
                    }else{
                        $xuni = array_rand($xuni_arr, $xuni_count);
                    }
                }
                if(empty($time)){
                    $sendtime = time() + 20;
                }else{
                    $sendtime = time() + $time;
                }
                if(empty($sitem['kjmsg_jiange'])){
                    $sitem['kjmsg_jiange'] = 60;
                }
                if(empty($sitem['kjmsg_jiange2'])){
                    $sitem['kjmsg_jiange2'] = 80;
                }
                if($sitem['kjmsg_jiange'] == $sitem['kjmsg_jiange2']){
                    $kjmsg_jiange2 = $sitem['kjmsg_jiange'];
                }
                foreach ($xuni as $key2 => $value){
                    $value = $xuni_arr[$value];
                    $hufu = array_rand($hufu_arr);
                    $hufu = $hufu_arr[$hufu];
                    if($hufu['type'] == 3){
                        $hufu_zh = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_hf') . " WHERE weid=" . $weid . " AND parentid=" . $hufu['id'] . " AND enabled=1 ORDER BY displayorder DESC,id ASC");
                        if(empty($hufu_zh)){
                            continue;
                        }else{
                            foreach ($hufu_zh as $key3 => $hufu){
                                $data = array('weid' => $weid, 'mid' => $mid, 'sendid' => $value['id'], 'type' => 2, 'yuedu' => 0,);
                                $data['createtime'] = $sendtime;
                                if($hufu['type'] == 0){
                                    $data['leixing'] = 0;
                                    $data['content'] = $hufu['name'];
                                    if(empty($data['content'])){
                                        $data['content'] = 'hello!';
                                    }
                                    pdo_insert("jy_ppp_xinxi_temp", $data);
                                }elseif ($hufu['type'] == 1){
                                    $data['leixing'] = 1;
                                    $data['content'] = "您有一张新的图片消息！";
                                    $data['picurl'] = tomedia($hufu['picurl']);
                                    pdo_insert("jy_ppp_xinxi_temp", $data);
                                }elseif ($hufu['type'] == 2){
                                    $data['leixing'] = 2;
                                    $data['content'] = "您有一条新的语音消息！";
                                    $data['media_id'] = tomedia($hufu['media_id']);
                                    pdo_insert("jy_ppp_xinxi_temp", $data);
                                }
                                if(empty($kjmsg_jiange2)){
                                    $kjmsg_jiange = mt_rand($sitem['kjmsg_jiange'], $sitem['kjmsg_jiange2']);
                                    $sendtime += $kjmsg_jiange;
                                }else{
                                    $sendtime += $kjmsg_jiange2;
                                }
                            }
                        }
                    }else{
                        $data = array('weid' => $weid, 'mid' => $mid, 'sendid' => $value['id'], 'type' => 2, 'yuedu' => 0,);
                        $data['createtime'] = $sendtime;
                        if($hufu['type'] == 0){
                            $data['leixing'] = 0;
                            $data['content'] = $hufu['name'];
                            if(empty($data['content'])){
                                $data['content'] = 'hello!';
                            }
                            pdo_insert("jy_ppp_xinxi_temp", $data);
                        }elseif ($hufu['type'] == 1){
                            $data['leixing'] = 1;
                            $data['content'] = "您有一张新的图片消息！";
                            $data['picurl'] = tomedia($hufu['picurl']);
                            pdo_insert("jy_ppp_xinxi_temp", $data);
                        }elseif ($hufu['type'] == 2){
                            $data['leixing'] = 2;
                            $data['content'] = "您有一条新的语音消息！";
                            $data['media_id'] = tomedia($hufu['media_id']);
                            pdo_insert("jy_ppp_xinxi_temp", $data);
                        }
                    }
                    if(empty($kjmsg_jiange2)){
                        $kjmsg_jiange = mt_rand($sitem['kjmsg_jiange'], $sitem['kjmsg_jiange2']);
                        $sendtime += $kjmsg_jiange;
                    }else{
                        $sendtime += $kjmsg_jiange2;
                    }
                }
            }
        }
        public function mailui(){
            global $_W, $_GPC;
            $weid = $_W['uniacid'];
            if(empty($weid)){
                $weid = $_W['account']['uniacid'];
            }
            $mailui = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_mailui') . " WHERE weid=" . $weid);
            if(empty($mailui)){
                return $_W['siteroot'] . "addons/jy_ppp/images/notice.jpg";
            }else{
                $mailui_num = count($mailui);
                if($mailui_num == 1){
                    return tomedia($mailui[0]['thumb']);
                }else{
                    $mailui_num = $mailui_num-1;
                    $rand_ui = mt_rand(0, $mailui_num);
                    return tomedia($mailui[$rand_ui]['thumb']);
                }
            }
        }
        public function doMobileMail(){
            global $_W, $_GPC;
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                $weixin = 0;
                $weid = $_GPC['i'];
                $mid = $_SESSION['mid'];
                if(!empty($mid)){
                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                }
            }else{
                $weixin = 1;
                $weid = $_W['uniacid'];
                $from_user = $_SESSION['jy_openid'];
                if(empty($from_user)){
                    echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'mail')) . "';
				</script>";
                }else{
                    $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                    if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                        unset($uid);
                    }else{
                        $uid = $member_temp['uid'];
                        unset($member_temp);
                    }
                    if(empty($uid)){
                        echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'mail')) . "';
					</script>";
                    }else{
                        $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                        $mid = $member['id'];
                    }
                }
            }
            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
            if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                $this -> unzhuce();
                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                $mid = $member['id'];
            }
            include "../addons/jy_ppp/inc/mobile/area.php";
            if(!empty($sitem['moni'])){
                if(empty($_SESSION['address']) || $_SESSION['address_time'] < time()){
                    $op = $_GPC['op'];
                    $op_foo = 'mail';
                    $moni = 1;
                    if($weixin == 1){
                        include $this -> template('addtest');
                        exit;
                    }
                }else{
                    $moni_province = $_SESSION['moni_province'];
                    $moni_city = $_SESSION['moni_city'];
                    if(empty($sitem['user_addr'])){
                        $moni_province .= $moni_city;
                    }elseif ($sitem['user_addr'] == 2){
                        $moni_province = $moni_city;
                    }
                    if(empty($moni_province)){
                        if(empty($member['province'])){
                            $member['province'] = 11;
                        }
                        $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                        if(empty($sitem['user_addr'])){
                            $moni_province = $province[$member['province']];
                            if(!empty($member['city'])){
                                $moni_province .= $sub_array[$member['province']][$member['city']];
                            }
                        }elseif ($sitem['user_addr'] == 2){
                            if(!empty($member['city'])){
                                $moni_province = $sub_array[$member['province']][$member['city']];
                            }else{
                                $moni_province = $province[$member['province']];
                            }
                        }else{
                            $moni_province = $province[$member['province']];
                        }
                    }
                }
            }
            if(!empty($member['id'])){
                $op = $_GPC['op'];
                $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                if($op == 'more'){
                    $str = $_GPC['str'];
                    if(!empty($str)){
                        $str = substr($str, 0 , -1);
                        $str = " AND sendid NOT IN ( " . $str . " ) ";
                        $list_id = pdo_fetchall("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . $str . " AND yuedu=0 GROUP BY sendid ORDER BY createtime DESC LIMIT 10 ");
                        if(count($list_id) < 10){
                            $temp_list_yu = 10 - intval(count($list_id));
                            $list_id2 = pdo_fetchall("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . $str . " AND yuedu=1 GROUP BY sendid ORDER BY createtime DESC LIMIT " . $temp_list_yu);
                            foreach ($list_id2 as $key => $value){
                                $list_id[] = $value;
                            }
                        }
                        $xinxi_temp_allow_id = '';
                        if(!empty($list_id)){
                            foreach ($list_id as $key => $value){
                                $xinxi_temp_allow_id .= $value['id'] . ",";
                            }
                            $xinxi_temp_allow_id = substr($xinxi_temp_allow_id, 0, -1);
                        }
                        if(!empty($xinxi_temp_allow_id)){
                            $list = pdo_fetchall("SELECT a.id,a.sendid as mid,a.type,a.createtime,a.yuedu,b.avatar,b.nicheng,b.province,b.city,b.type as type2,b.brith,b.sex,c.height FROM " . tablename('jy_ppp_xinxi') . " as a left join " . tablename('jy_ppp_member') . " as b on a.sendid=b.id left join " . tablename('jy_ppp_basic') . " as c on a.sendid=c.mid WHERE a.weid=" . $weid . " AND a.id IN (" . $xinxi_temp_allow_id . ") ORDER BY a.yuedu ASC,a.createtime DESC");
                        }
                        $html = '';
                        foreach ($list as $key => $l){
                            if($l['type'] != 3){
                                if(!empty($l['avatar'])){
                                    $avatar = tomedia($l['avatar']);
                                }else{
                                    if($l['sex'] == 1){
                                        $avatar = "../addons/jy_ppp/images/boy.png";
                                    }else{
                                        $avatar = "../addons/jy_ppp/images/girl.png";
                                    }
                                }
                            }else{
                                if (!empty($sitem['adminthumb'])){
                                    $avatar = tomedia($sitem['adminthumb']);
                                }else{
                                    $avatar = "../addons/jy_ppp/images/adminthumb.png";
                                }
                            }
                            $avatar = '<img src=' . $avatar . '>';
                            $title = '';
                            if($l['type'] != 3){
                                $title = '<b class="tit">' . $l['nicheng'] . '<span class="pink">';
                                if(empty($l['yuedu'])){
                                    if($l['type'] == 1){
                                        $title .= '[会员推荐]' . '</span>';
                                    }
                                    if($l['type'] == 2){
                                        $title .= '[最新回信]' . '</span>';
                                    }
                                }
                            }else{
                                $title = '<b class="tit">管理员<span class="pink"></span>';
                            }
                            $ziliao = '';
                            if($l['type'] != 3){
                                if(!empty($l['brith'])){
                                    $birthday = $l['brith'];
                                    $now = time();
                                    $month = 0;
                                    if(date('m', $now) > date('m', $birthday)) $month = 1;
                                    if(date('m', $now) == date('m', $birthday)) if(date('d', $now) >= date('d', $birthday)) $month = 1;
                                        $nianlin = date('Y', $now) - date('Y', $birthday) + $month;
                                        $ziliao .= $nianlin . "岁·";
                                    }
                                    if(!empty($moni_province) && $l['type2'] == 3){
                                        $ziliao .= $moni_province;
                                    }else{
                                        if(!empty($l['province'])){
                                            if(empty($l['city'])){
                                                $ziliao .= $province[$l['province']];
                                            }else{
                                                $ziliao .= $province[$l['province']] . $sub_array[$l['province']][$l['city']];
                                            }
                                        }
                                    }
                                    if (!empty($l['height'])){
                                        $ziliao .= '·' . $l['height'] . 'cm';
                                    }
                                }
                                if($l['yuedu'] == 0){
                                    $yuedu = '<i class="cycle_tag">未读</i>';
                                }else{
                                    $yuedu = '';
                                }
                                $html .= "<a href=" . $this -> createMobileUrl('chat', array('id' => $l['mid'])) . '><ul class="disbox-hor user-list mailid" data-id="' . $l['mid'] . '"><li class="foot-icon disbox-center">' . $avatar . '</li><li class="disbox-flex user_mession">' . $title . '</b><p class="bot">' . $ziliao . '</p></li><div class="right">' . date("G:i", $l['createtime']) . '</div>' . $yuedu . '</ul></a>';
                            }
                            if(count($list) > 9){
                                echo json_encode(array('status' => 1, 'log' => $html));
                                exit;
                            }else{
                                echo json_encode(array('status' => 2, 'log' => $html));
                                exit;
                            }
                        }else{
                            echo 3;
                            exit;
                        }
                    }else{
                        $temp_log = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_login_log') . " WHERE weid=" . $weid . " AND mid=" . $mid . " ORDER BY createtime DESC LIMIT 1 ");
                        $today = strtotime("today");
                        if($temp_log['createtime'] < $today){
                            $data = array('weid' => $weid, 'mid' => $mid, 'createtime' => TIMESTAMP,);
                            pdo_insert('jy_ppp_login_log', $data);
                            $this -> sendxuni($member);
                            echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhaohu', array('foo' => 'mail')) . "';
					</script>";
                            exit;
                        }else{
                            $menu = 2;
                            $lianxi = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND type!=0");
                            $visit = pdo_fetch("SELECT b.nicheng FROM " . tablename('jy_ppp_visit') . " as a left join " . tablename('jy_ppp_member') . " as b on a.visitid=b.id WHERE a.weid=" . $weid . " AND a.mid=" . $mid . " ORDER BY a.createtime DESC LIMIT 1");
                            $list_id = pdo_fetchall("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND yuedu=0 GROUP BY sendid ORDER BY createtime DESC LIMIT 10 ");
                            if(count($list_id) < 10){
                                $temp_list_yu = 10 - intval(count($list_id));
                                $list_id2 = pdo_fetchall("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND yuedu=1 GROUP BY sendid ORDER BY createtime DESC LIMIT " . $temp_list_yu);
                                foreach ($list_id2 as $key => $value){
                                    $list_id[] = $value;
                                }
                            }
                            $xinxi_temp_allow_id = '';
                            if(!empty($list_id)){
                                foreach ($list_id as $key => $value){
                                    $xinxi_temp_allow_id .= $value['id'] . ",";
                                }
                                $xinxi_temp_allow_id = substr($xinxi_temp_allow_id, 0, -1);
                            }
                            if(!empty($xinxi_temp_allow_id)){
                                $list = pdo_fetchall("SELECT a.id,a.sendid as mid,a.type,a.createtime,a.yuedu,b.avatar,b.nicheng,b.province,b.city,b.type as type2,b.brith,b.sex,c.height FROM " . tablename('jy_ppp_xinxi') . " as a left join " . tablename('jy_ppp_member') . " as b on a.sendid=b.id left join " . tablename('jy_ppp_basic') . " as c on a.sendid=c.mid WHERE a.weid=" . $weid . " AND a.id IN (" . $xinxi_temp_allow_id . ") ORDER BY a.createtime DESC");
                            }
                            $weidu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND yuedu=0 AND mid= " . $mid . " AND createtime<=" . time());
                            include $this -> template('mail');
                        }
                    }
                }else{
                    if($weixin == 1){
                        echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
                    }else{
                        echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                    }
                }
            }
            public function doMobileLuck(){
                global $_W, $_GPC;
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                    $weixin = 0;
                    $weid = $_GPC['i'];
                    $mid = $_SESSION['mid'];
                    if(!empty($mid)){
                        $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                    }
                }else{
                    $weixin = 1;
                    $weid = $_W['uniacid'];
                    $from_user = $_SESSION['jy_openid'];
                    if(empty($from_user)){
                        echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'luck')) . "';
				</script>";
                    }else{
                        $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                        if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                            unset($uid);
                        }else{
                            $uid = $member_temp['uid'];
                            unset($member_temp);
                        }
                        if(empty($uid)){
                            echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'luck')) . "';
					</script>";
                        }else{
                            $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                            $mid = $member['id'];
                        }
                    }
                }
                $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                    $this -> unzhuce();
                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                    $mid = $member['id'];
                }
                include "../addons/jy_ppp/inc/mobile/area.php";
                if(!empty($sitem['moni'])){
                    if(empty($_SESSION['address']) || $_SESSION['address_time'] < time()){
                        $op = $_GPC['op'];
                        $op_foo = 'mail';
                        $moni = 1;
                        if($weixin == 1){
                            include $this -> template('addtest');
                            exit;
                        }
                    }else{
                        $moni_province = $_SESSION['moni_province'];
                        $moni_city = $_SESSION['moni_city'];
                        if(empty($sitem['user_addr'])){
                            $moni_province .= $moni_city;
                        }elseif ($sitem['user_addr'] == 2){
                            $moni_province = $moni_city;
                        }
                        if(empty($moni_province)){
                            if(empty($member['province'])){
                                $member['province'] = 11;
                            }
                            $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                            if(empty($sitem['user_addr'])){
                                $moni_province = $province[$member['province']];
                                if(!empty($member['city'])){
                                    $moni_province .= $sub_array[$member['province']][$member['city']];
                                }
                            }elseif ($sitem['user_addr'] == 2){
                                if(!empty($member['city'])){
                                    $moni_province = $sub_array[$member['province']][$member['city']];
                                }else{
                                    $moni_province = $province[$member['province']];
                                }
                            }else{
                                $moni_province = $province[$member['province']];
                            }
                        }
                    }
                }
                if(!empty($member['id'])){
                    $temp_log = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_login_log') . " WHERE weid=" . $weid . " AND mid=" . $mid . " ORDER BY createtime DESC LIMIT 1 ");
                    $today = strtotime("today");
                    if($temp_log['createtime'] < $today){
                        $data = array('weid' => $weid, 'mid' => $mid, 'createtime' => TIMESTAMP,);
                        pdo_insert('jy_ppp_login_log', $data);
                        $this -> sendxuni($member);
                        echo "<script>
					window.location.href = '" . $this -> createMobileUrl('zhaohu', array('foo' => 'luck')) . "';
				</script>";
                        message($today);
                        exit;
                    }else{
                        $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                        $op = $_GPC['op'];
                        if($op == 'zhaohu2'){
                            $id = $_GPC['id'];
                            $today = strtotime("today");
                            $temp = pdo_fetch("SELECT createtime FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND mid=" . $id . " AND type=0 ORDER BY createtime DESC LIMIT 1 ");
                            if(!empty($temp) && $temp['createtime'] > $today){
                                echo 2;
                            }else{
                                $data = array('weid' => $weid, 'mid' => $id, 'sendid' => $mid, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                                if($member['sex'] == 2){
                                    $wenti = pdo_fetch("SELECT id,name FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND parentid=0 AND enabled=1 " . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_zhaohu') . " ) ORDER BY id LIMIT 1");
                                    $data['content'] = $wenti['name'];
                                    $data['zhaohuid'] = $wenti['id'];
                                    if(empty($data['content'])){
                                        $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                    }
                                }else{
                                    $basic = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_basic') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                    $desc = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_desc') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                    $aihao = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                                    $tezheng = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                                    $temp_wt = array();
                                    if(!empty($member['brith'])){
                                        array_push($temp_wt, '1', '2', '3');
                                        if(!empty($basic['height'])){
                                            array_push($temp_wt, '4');
                                        }
                                    }
                                    if(!empty($member['province'])){
                                        array_push($temp_wt, '7');
                                        if(!empty($aihao)){
                                            array_push($temp_wt, '6');
                                        }
                                        if(!empty($tezheng)){
                                            array_push($temp_wt, '5');
                                        }
                                    }
                                    if(!empty($basic['blood'])){
                                        array_push($temp_wt, '8');
                                    }
                                    if(!empty($desc['leixin'])){
                                        array_push($temp_wt, '9');
                                    }
                                    if(!empty($aihao)){
                                        array_push($temp_wt, '10');
                                    }
                                    if(!empty($tezheng)){
                                        array_push($temp_wt, '11');
                                    }
                                    array_push($temp_wt, '12', '13', '14', '15', '16', '17', '18');
                                    $temp_num = count($temp_wt)-1;
                                    $xiabiao = mt_rand(0, $temp_num);
                                    $rand_wt = $temp_wt[$xiabiao];
                                    switch ($rand_wt){
                                    case 1: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,有时间可以聊会儿天么？";
                                        break;
                                    case 2: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,希望能够了解你，可以交个朋友吗？";
                                        break;
                                    case 3: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,我觉得我们挺合适的。";
                                        break;
                                    case 4: $data['content'] = "可以认识一下么？我生于" . date('Y', $member['brith']) . "年,身高" . $basic['height'] . "cm,我觉得我们挺合适的。";
                                        break;
                                    case 5: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",现居住在" . $province[$member['province']] . "，希望能够了解你，你怎么看？";
                                        break;
                                    case 6: $data['content'] = "嗨！我居住在" . $province[$member['province']] . "，平时喜欢" . $aihao['aihao'] . "，希望你能和你交朋友哈！";
                                        break;
                                    case 7: $data['content'] = "嗨！我是来自" . $province[$member['province']] . "的年轻人，希望你会记得我哈~";
                                        break;
                                    case 8: $data['content'] = "嗨！我是" . $basic['blood'] . "血型的,不知道你是不是也是这样的呢？";
                                        break;
                                    case 9: $data['content'] = "哈喽！我喜欢" . $basic['leixin'] . "的女生,不知道你是不是也是这样的呢？";
                                        break;
                                    case 10: $data['content'] = "哈喽！我平时很喜欢" . $aihao['aihao'] . ",不知道你是不是也是这样的呢？";
                                        break;
                                    case 11: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",希望能够了解你，可以交个朋友吗？";
                                        break;
                                    case 12: $data['content'] = "哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
                                        break;
                                    case 13: $data['content'] = "Hi,我觉得你很不错，有时间可以聊会儿天么？";
                                        break;
                                    case 14: $data['content'] = "hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
                                        break;
                                    case 15: $data['content'] = "hey，我觉得你很有气质，可以认识你吗？";
                                        break;
                                    case 16: $data['content'] = "我们好像在那里见过，可以认识你吗？";
                                        break;
                                    case 17: $data['content'] = "hey，我觉得你的打扮很多看，可以认识你吗？";
                                        break;
                                    default: $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                        break;
                                    }
                                }
                                pdo_insert("jy_ppp_xinxi", $data);
                                $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                if($xuni_temp['type'] == 3){
                                    $this -> sendxuni($member, $xuni_temp);
                                }
                                echo 1;
                                $kefu_member = pdo_fetch("SELECT type,from_user,nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                $send_member = pdo_fetch("SELECT nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                                if($kefu_member['type'] != 3 && !empty($kefu_member['from_user'])){
                                    $send_kefu_tf = false;
                                    $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                    if($sitem['jiange'] > 0){
                                        $temp_kefu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " ORDER BY createtime DESC LIMIT 1 ");
                                        $temp_jg_time = time() - $temp_kefu['createtime'];
                                        if($temp_jg_time > $sitem['jiange'] * 60){
                                            $today = strtotime('today');
                                            $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                            if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                $send_kefu_tf = true;
                                            }
                                        }
                                    }else{
                                        $today = strtotime('today');
                                        $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                        if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                            $send_kefu_tf = true;
                                        }
                                    }
                                    if($send_kefu_tf == true){
                                        $text2 = "附近的'" . $send_member['nicheng'] . "'给你发了一封表白信";
                                        $text = urlencode($text2);
                                        $desc2 = "点击下方【信箱】按钮查看所有来信";
                                        $desc = urlencode($desc2);
                                        $pic2 = $this -> mailui();
                                        $pic = urlencode($pic2);
                                        $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2);
                                        $url = urlencode($url2);
                                        $access_token = WeAccount :: token();
                                        $data = array("touser" => $kefu_member['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $desc, "url" => $url, 'picurl' => $pic)))));
                                        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                                        load() -> func('communication');
                                        $response = ihttp_request($url, urldecode(json_encode($data)));
                                        $errcode = json_decode($response['content'], true);
                                        $data3 = array('weid' => $weid, 'mid' => $id, 'type' => 'news', 'content' => $text2, 'desc' => $desc2, 'url' => $url2, 'picurl' => $pic2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                                        pdo_insert("jy_ppp_kefu", $data3);
                                    }
                                }
                            }
                            exit;
                        }elseif ($op == 'zhaohu'){
                            $str = $_GPC['str'];
                            if(!empty($str)){
                                $str = substr($str, 0, -1);
                            }else{
                                echo 1;
                                exit;
                            }
                            $zh = explode(",", $str);
                            if($member['sex'] == 1){
                                $basic = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_basic') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                $desc = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_desc') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                $aihao = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                                $tezheng = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $mid . " LIMIT 1");
                                $temp_wt = array();
                                if(!empty($member['brith'])){
                                    array_push($temp_wt, '1', '2', '3');
                                    if(!empty($basic['height'])){
                                        array_push($temp_wt, '4');
                                    }
                                }
                                if(!empty($member['province'])){
                                    array_push($temp_wt, '7');
                                    if(!empty($aihao)){
                                        array_push($temp_wt, '6');
                                    }
                                    if(!empty($tezheng)){
                                        array_push($temp_wt, '5');
                                    }
                                }
                                if(!empty($basic['blood'])){
                                    array_push($temp_wt, '8');
                                }
                                if(!empty($desc['leixin'])){
                                    array_push($temp_wt, '9');
                                }
                                if(!empty($aihao)){
                                    array_push($temp_wt, '10');
                                }
                                if(!empty($tezheng)){
                                    array_push($temp_wt, '11');
                                }
                                array_push($temp_wt, '12', '13', '14', '15', '16', '17', '18');
                                $temp_num = count($temp_wt)-1;
                            }
                            $temp_time = 10;
                            foreach ($zh as $key => $value){
                                $temp = pdo_fetch("SELECT createtime FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND mid=" . $value . " AND type=0 ORDER BY createtime DESC LIMIT 1 ");
                                if(!empty($temp) && $temp['createtime'] > $today){
                                }else{
                                    $data = array('weid' => $weid, 'mid' => $value, 'sendid' => $mid, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                                    if($member['sex'] == 2){
                                        $wenti = pdo_fetch("SELECT id,name FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND parentid=0 AND enabled=1 " . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_zhaohu') . " ) ORDER BY id LIMIT 1");
                                        $data['content'] = $wenti['name'];
                                        $data['zhaohuid'] = $wenti['id'];
                                        if(empty($data['content'])){
                                            $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                        }
                                    }else{
                                        $xiabiao = mt_rand(0, $temp_num);
                                        $rand_wt = $temp_wt[$xiabiao];
                                        switch ($rand_wt){
                                        case 1: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,有时间可以聊会儿天么？";
                                            break;
                                        case 2: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,希望能够了解你，可以交个朋友吗？";
                                            break;
                                        case 3: $data['content'] = "哈喽！我生于" . date('Y', $member['brith']) . "年,我觉得我们挺合适的。";
                                            break;
                                        case 4: $data['content'] = "可以认识一下么？我生于" . date('Y', $member['brith']) . "年,身高" . $basic['height'] . "cm,我觉得我们挺合适的。";
                                            break;
                                        case 5: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",现居住在" . $province[$member['province']] . "，希望能够了解你，你怎么看？";
                                            break;
                                        case 6: $data['content'] = "嗨！我居住在" . $province[$member['province']] . "，平时喜欢" . $aihao['aihao'] . "，希望你能和你交朋友哈！";
                                            break;
                                        case 7: $data['content'] = "嗨！我是来自" . $province[$member['province']] . "的年轻人，希望你会记得我哈~";
                                            break;
                                        case 8: $data['content'] = "嗨！我是" . $basic['blood'] . "血型的,不知道你是不是也是这样的呢？";
                                            break;
                                        case 9: $data['content'] = "哈喽！我喜欢" . $basic['leixin'] . "的女生,不知道你是不是也是这样的呢？";
                                            break;
                                        case 10: $data['content'] = "哈喽！我平时很喜欢" . $aihao['aihao'] . ",不知道你是不是也是这样的呢？";
                                            break;
                                        case 11: $data['content'] = "哈喽！我个人很" . $tezheng['tezheng'] . ",希望能够了解你，可以交个朋友吗？";
                                            break;
                                        case 12: $data['content'] = "哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
                                            break;
                                        case 13: $data['content'] = "Hi,我觉得你很不错，有时间可以聊会儿天么？";
                                            break;
                                        case 14: $data['content'] = "hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
                                            break;
                                        case 15: $data['content'] = "hey，我觉得你很有气质，可以认识你吗？";
                                            break;
                                        case 16: $data['content'] = "我们好像在那里见过，可以认识你吗？";
                                            break;
                                        case 17: $data['content'] = "hey，我觉得你的打扮很多看，可以认识你吗？";
                                            break;
                                        default: $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                            break;
                                        }
                                    }
                                    pdo_insert("jy_ppp_xinxi", $data);
                                    $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $value);
                                    if($xuni_temp['type'] == 3){
                                        $temp_time += mt_rand(10, 100);
                                        $this -> sendxuni($member, $xuni_temp, $temp_time);
                                    }
                                    $kefu_member = pdo_fetch("SELECT type,from_user,nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $value);
                                    $send_member = pdo_fetch("SELECT nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                                    if($kefu_member['type'] != 3 && !empty($kefu_member['from_user'])){
                                        $send_kefu_tf = false;
                                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                        if($sitem['jiange'] > 0){
                                            $temp_kefu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $value . " ORDER BY createtime DESC LIMIT 1 ");
                                            $temp_jg_time = time() - $temp_kefu['createtime'];
                                            if($temp_jg_time > $sitem['jiange'] * 60){
                                                $today = strtotime('today');
                                                $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $value . " AND createtime>" . $today);
                                                if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                    $send_kefu_tf = true;
                                                }
                                            }
                                        }else{
                                            $today = strtotime('today');
                                            $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $value . " AND createtime>" . $today);
                                            if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                $send_kefu_tf = true;
                                            }
                                        }
                                        if($send_kefu_tf == true){
                                            $text2 = "附近的'" . $send_member['nicheng'] . "'给你发了一封表白信";
                                            $text = urlencode($text2);
                                            $desc2 = "点击下方【信箱】按钮查看所有来信";
                                            $desc = urlencode($desc2);
                                            $pic2 = $this -> mailui();
                                            $pic = urlencode($pic2);
                                            $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2);
                                            $url = urlencode($url2);
                                            $access_token = WeAccount :: token();
                                            $data = array("touser" => $kefu_member['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $desc, "url" => $url, 'picurl' => $pic)))));
                                            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                                            load() -> func('communication');
                                            $response = ihttp_request($url, urldecode(json_encode($data)));
                                            $errcode = json_decode($response['content'], true);
                                            $data3 = array('weid' => $weid, 'mid' => $value, 'type' => 'news', 'content' => $text2, 'desc' => $desc2, 'url' => $url2, 'picurl' => $pic2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_kefu", $data3);
                                        }
                                    }
                                }
                            }
                            echo 1;
                            exit;
                        }elseif($op == 'more'){
                            $str = $_GPC['str'];
                            if(empty($str)){
                                echo json_encode(array('status' => 3));
                                exit;
                            }else{
                                $str = substr($str, 0, -1);
                            }
                            $condition = $condition .= " AND a.id NOT IN ( " . $str . " ) ";
                            if($member['sex'] == 1){
                                $condition .= " AND sex=2 ";
                            }else{
                                $condition .= " AND sex=1 ";
                            }
                            $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                            if(!empty($match)){
                                if($match['province'] > 0){
                                    $temp_province = $match['province'];
                                }else{
                                    if(!empty($member['province'])){
                                        $temp_province = $member['province'];
                                    }else{
                                        $temp_province = 11;
                                    }
                                }
                                if(!empty($moni_province)){
                                    $condition .= " AND ( a.type=3 OR (a.type!=3 AND a.province= " . $temp_province . ")   ) ";
                                    $condition .= " AND  a.type=3  ";
                                }else{
                                    $condition .= " AND a.province= " . $temp_province;
                                }
                            }else{
                                if(!empty($moni_province)){
                                    $condition .= " AND  a.type=3  ";
                                }
                            }
                            if($member['sex'] == 2){
                                $xz_sex = "女生";
                            }else{
                                $xz_sex = "男生";
                            }
                            $today = strtotime("today");
                            if(empty($sitem['sql_style'])){
                                $temp_tjid = pdo_fetchcolumn("SELECT MAX(id) FROM " . tablename("jy_ppp_member") . " WHERE weid=" . $weid);
                                $temp_tjid_rand = mt_rand(0, 8000);
                                $temp_tjid_rand = $temp_tjid_rand / 10000.0;
                                $temp_tjid = floor($temp_tjid * $temp_tjid_rand);
                                $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.city,a.type as type2,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . " AND a.id >= " . $temp_tjid . " LIMIT 20");
                                if(count($tuijian) > 10){
                                    $ttt = array_rand($tuijian, 10);
                                    $tuijian_temp = array();
                                    foreach ($ttt as $key => $value){
                                        $tuijian_temp[] = $tuijian[$value];
                                    }
                                    $tuijian = $tuijian_temp;
                                }
                            }else{
                                $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.city,a.type as type2,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . "  LIMIT 200");
                                if(count($tuijian) > 10){
                                    $ttt = array_rand($tuijian, 10);
                                    $tuijian_temp = array();
                                    foreach ($ttt as $key => $value){
                                        $tuijian_temp[] = $tuijian[$value];
                                    }
                                    $tuijian = $tuijian_temp;
                                }
                            }
                            $html = '';
                            if(!empty($tuijian)){
                                foreach ($tuijian as $key => $l){
                                    if(!empty($l['avatar'])){
                                        $avatar = tomedia($l['avatar']);
                                    }else{
                                        if($l['sex'] == 1){
                                            $avatar = "../addons/jy_ppp/images/boy.png";
                                        }else{
                                            $avatar = "../addons/jy_ppp/images/girl.png";
                                        }
                                    }
                                    $ziliao = '';
                                    if(!empty($moni_province) && $l['type2'] == 3){
                                        $ziliao .= $moni_province;
                                    }else{
                                        if(!empty($l['province'])){
                                            if(empty($l['city'])){
                                                $ziliao .= $province[$l['province']];
                                            }else{
                                                $ziliao .= $province[$l['province']] . $sub_array[$l['province']][$l['city']];
                                            }
                                        }
                                    }
                                    if(!empty($l['brith'])){
                                        $birthday = $l['brith'];
                                        $now = time();
                                        $month = 0;
                                        if(date('m', $now) > date('m', $birthday)) $month = 1;
                                        if(date('m', $now) == date('m', $birthday)) if(date('d', $now) >= date('d', $birthday)) $month = 1;
                                            $nianlin = date('Y', $now) - date('Y', $birthday) + $month;
                                            $ziliao .= $nianlin . "岁";
                                        }
                                        if(!empty($l['height'])){
                                            $ziliao .= "·" . $l['height'] . "cm";
                                        }
                                        if(!empty($l['beizhu'])){
                                            $match = $l['beizhu'];
                                        }else{
                                            if (!empty($l['province2'])){
                                                $match = "寻找" . $province[$l['province2']];
                                                if(!empty($l['age'])){
                                                    $match .= " " . $match_age[$l['age']];
                                                }
                                                if(!empty($l['height2'])){
                                                    $match .= " " . $match_height[$l['height2']] . "cm";
                                                }
                                                $match .= "的" . $xz_sex;
                                            }else{
                                                $match = "寻找";
                                                if (!empty($l['province'])){
                                                    $match .= $province[$l['province']];
                                                }else{
                                                    $match .= $province[11];
                                                }
                                                $match .= "的" . $xz_sex;
                                            }
                                        }
                                        $a_zh = '<span class="hai_hello" onclick="sayHello(this,' . $l['id'] . ')" style="font-size:13px" >打招呼</span>';
                                        $html .= '<ul class="disbox-hor user-list" data-id="' . $l['id'] . '"><li class="foot-icon"><a href="' . $this -> createMobileUrl('detail', array('id' => $l['id'])) . '"><img src="' . $avatar . '" /></a></li><li class="disbox-flex user_mession"><a href="' . $this -> createMobileUrl('detail', array('id' => $l['id'])) . '"><b class="tit">' . $l['nicheng'] . '</b><p class="bot">' . $ziliao . '</p><p class="foot_imgarrange">' . $match . '</p></a></li><div><p class="fujin">' . $this -> xuniaddress($l['id']) . '公里</p>' . $a_zh . '</div></ul>';
                                    }
                                }
                                if(count($tuijian) < 10){
                                    echo json_encode(array('status' => 2, 'log' => $html));
                                    exit;
                                }else{
                                    echo json_encode(array('status' => 1, 'log' => $html));
                                    exit;
                                }
                            }else{
                                $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                $menu = 3;
                                if($member['sex'] == 1){
                                    $condition .= " AND sex=2 ";
                                }else{
                                    $condition .= " AND sex=1 ";
                                }
                                $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                if(!empty($match)){
                                    if($match['province'] > 0){
                                        $temp_province = $match['province'];
                                    }else{
                                        if(!empty($member['province'])){
                                            $temp_province = $member['province'];
                                        }else{
                                            $temp_province = 11;
                                        }
                                    }
                                    if(!empty($moni_province)){
                                        $condition .= " AND ( a.type=3 OR (a.type!=3 AND a.province= " . $temp_province . ")   ) ";
                                    }else{
                                        $condition .= " AND a.province= " . $temp_province;
                                    }
                                }else{
                                    if(!empty($moni_province)){
                                        $condition .= " AND  a.type=3  ";
                                    }
                                }
                                if($member['sex'] == 2){
                                    $xz_sex = "女生";
                                }else{
                                    $xz_sex = "男生";
                                }
                                if(empty($sitem['sql_style'])){
                                    $temp_tjid = pdo_fetchcolumn("SELECT MAX(id) FROM " . tablename("jy_ppp_member") . " WHERE weid=" . $weid);
                                    $temp_tjid_rand = mt_rand(0, 8000);
                                    $temp_tjid_rand = $temp_tjid_rand / 10000.0;
                                    $temp_tjid = floor($temp_tjid * $temp_tjid_rand);
                                    $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,a.city,a.type as type2,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . " AND a.id >= " . $temp_tjid . " LIMIT 20");
                                    if(count($tuijian) > 10){
                                        $ttt = array_rand($tuijian, 10);
                                        $tuijian_temp = array();
                                        foreach ($ttt as $key => $value){
                                            $tuijian_temp[] = $tuijian[$value];
                                        }
                                        $tuijian = $tuijian_temp;
                                    }
                                }else{
                                    $tuijian = pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,a.city,a.type as type2,b.height,c.age,c.height as height2,c.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_match') . " as c on a.id=c.mid WHERE a.weid=" . $weid . $condition . " LIMIT 200");
                                    if(count($tuijian) > 10){
                                        $ttt = array_rand($tuijian, 10);
                                        $tuijian_temp = array();
                                        foreach ($ttt as $key => $value){
                                            $tuijian_temp[] = $tuijian[$value];
                                        }
                                        $tuijian = $tuijian_temp;
                                    }
                                }
                                $weidu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND yuedu=0 AND mid= " . $mid . " AND createtime<=" . time());
                                include $this -> template('luck');
                            }
                        }
                    }else{
                        if($weixin == 1){
                            echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
                        }else{
                            echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                        }
                    }
                }
                public function doMobileGeren(){
                    global $_W, $_GPC;
                    $xuniid = intval($_GPC['xuniid']);
                    if(empty($xuniid)){
                        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                            $weixin = 0;
                            $weid = $_GPC['i'];
                            $mid = $_SESSION['mid'];
                            if(!empty($mid)){
                                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                            }
                        }else{
                            $weixin = 1;
                            $weid = $_W['uniacid'];
                            $from_user = $_SESSION['jy_openid'];
                            if(empty($from_user)){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'geren')) . "';
					</script>";
                            }else{
                                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                    unset($uid);
                                }else{
                                    $uid = $member_temp['uid'];
                                    unset($member_temp);
                                }
                                if(empty($uid)){
                                    echo "<script>
							window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'geren')) . "';
						</script>";
                                }else{
                                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                                    $mid = $member['id'];
                                }
                            }
                        }
                    }else{
                        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                            $weixin = 0;
                            $weid = $_GPC['i'];
                            $dyid = $_SESSION['dyid'];
                            if(!empty($dyid)){
                                $dianyuan = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_dianyuan') . " WHERE weid=" . $weid . " AND id=" . $dyid);
                            }
                        }else{
                            $weixin = 1;
                            $weid = $_W['uniacid'];
                            $from_user = $_SESSION['jy_openid'];
                            if(empty($from_user)){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'geren')) . "';
					</script>";
                            }else{
                                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                    unset($uid);
                                }else{
                                    $uid = $member_temp['uid'];
                                    unset($member_temp);
                                }
                                if(empty($uid)){
                                    echo "<script>
							window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'geren')) . "';
						</script>";
                                }else{
                                    $dianyuan = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_dianyuan') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                                    $dyid = $dianyuan['id'];
                                }
                            }
                        }
                        if(empty($dianyuan)){
                            echo "<script>
					window.location.href = '" . $this -> createMobileUrl('dy_login') . "';
				</script>";
                        }else{
                            $xuni_qx = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_xuni_member') . " WHERE weid=" . $weid . " AND mid=" . $xuniid . " AND dyid=" . $dyid);
                            if(empty($xuni_qx)){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('dy_user') . "';
					</script>";
                            }else{
                                $mid = $xuniid;
                                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                            }
                        }
                    }
                    $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                    if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                        $this -> unzhuce();
                        $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                        $mid = $member['id'];
                    }
                    if(!empty($member)){
                        $temp_log = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_login_log') . " WHERE weid=" . $weid . " AND mid=" . $mid . " ORDER BY createtime DESC LIMIT 1 ");
                        if (empty($_GPC['xuniid']) || $member['type'] != 3){
                            $today = strtotime("today");
                        }else{
                            $today = 0;
                        }
                        if($temp_log['createtime'] < $today){
                            $data = array('weid' => $weid, 'mid' => $mid, 'createtime' => TIMESTAMP,);
                            pdo_insert('jy_ppp_login_log', $data);
                            $this -> sendxuni($member);
                            echo "<script>
					window.location.href = '" . $this -> createMobileUrl('zhaohu', array('foo' => 'geren')) . "';
				</script>";
                            message($today);
                            exit;
                        }else{
                            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                            $menu = 4;
                            $birthday = $member['brith'];
                            if(empty($birthday)){
                                $birthday = 662688000;
                            }
                            $now = time();
                            $month = 0;
                            if(date('m', $now) > date('m', $birthday)) $month = 1;
                            if(date('m', $now) == date('m', $birthday)) if(date('d', $now) >= date('d', $birthday)) $month = 1;
                                $nianlin = date('Y', $now) - date('Y', $birthday) + $month;
                                if(empty($member['baoyue'])){
                                    $baoyue = 0;
                                }else{
                                    $baoyue = $member['baoyue'] - time();
                                    if($baoyue <= 0){
                                        $baoyue = 0;
                                    }else{
                                        $baoyue = round($baoyue / 86400);
                                    }
                                }
                                if(empty($member['shouxin'])){
                                    $shouxin = 0;
                                }else{
                                    $shouxin = $member['shouxin'] - time();
                                    if($shouxin <= 0){
                                        $shouxin = 0;
                                    }else{
                                        $shouxin = round($shouxin / 86400);
                                    }
                                }
                                $basic = pdo_fetch("SELECT blank FROM " . tablename('jy_ppp_basic') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                $desc = pdo_fetch("SELECT blank FROM " . tablename('jy_ppp_desc') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                $weidu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND yuedu=0 AND mid= " . $mid . " AND createtime<=" . time());
                                include $this -> template('geren');
                            }
                        }else{
                            if($weixin == 1){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
                            }else{
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                            }
                        }
                    }
                    public function xuniaddress($id){
                        global $_W, $_GPC;
                        $day = date('j');
                        $month = date('n');
                        $id = $id;
                        $yu = intval($id / 48);
                        $zhi = $id - $yu * 48;
                        $yu2 = intval($id / 7);
                        $zhi2 = $id - $yu2 * 7;
                        $zhi = $zhi * $zhi2;
                        if($day > $month){
                            $zhi = ($zhi + $day * 2 - $month * 3) / 10;
                        }else{
                            $zhi = ($zhi + $month * 3 - $day * 2) / 10;
                        }
                        if($zhi > 5){
                            $zhi = $zhi-5;
                        }elseif ($zhi > 3){
                            $zhi = $zhi-3;
                        }
                        if($zhi < 0){
                            $zhi = 0.5;
                        }
                        return $zhi;
                    }
                    public function doMobileDetail(){
                        global $_W, $_GPC;
                        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                            $weixin = 0;
                            $weid = $_GPC['i'];
                            $mid = $_SESSION['mid'];
                            if(!empty($mid)){
                                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                            }
                        }else{
                            $weixin = 1;
                            $weid = $_W['uniacid'];
                            $from_user = $_SESSION['jy_openid'];
                            if(empty($from_user)){
                                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'detail', 'id' => $_GPC['id'])) . "';
				</script>";
                            }else{
                                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                    unset($uid);
                                }else{
                                    $uid = $member_temp['uid'];
                                    unset($member_temp);
                                }
                                if(empty($uid)){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'detail', 'id' => $_GPC['id'])) . "';
					</script>";
                                }else{
                                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                                    $mid = $member['id'];
                                }
                            }
                        }
                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                        if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                            $this -> unzhuce();
                            $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                            $mid = $member['id'];
                        }
                        include "../addons/jy_ppp/inc/mobile/area.php";
                        if(!empty($sitem['moni'])){
                            if(empty($_SESSION['address']) || $_SESSION['address_time'] < time()){
                                $op = $_GPC['op'];
                                $op_foo = 'mail';
                                $moni = 1;
                                if($weixin == 1){
                                    include $this -> template('addtest');
                                    exit;
                                }
                            }else{
                                $moni_province = $_SESSION['moni_province'];
                                $moni_city = $_SESSION['moni_city'];
                                if(empty($sitem['user_addr'])){
                                    $moni_province .= $moni_city;
                                }elseif ($sitem['user_addr'] == 2){
                                    $moni_province = $moni_city;
                                }
                                if(empty($moni_province)){
                                    if(empty($member['province'])){
                                        $member['province'] = 11;
                                    }
                                    $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                                    if(empty($sitem['user_addr'])){
                                        $moni_province = $province[$member['province']];
                                        if(!empty($member['city'])){
                                            $moni_province .= $sub_array[$member['province']][$member['city']];
                                        }
                                    }elseif ($sitem['user_addr'] == 2){
                                        if(!empty($member['city'])){
                                            $moni_province = $sub_array[$member['province']][$member['city']];
                                        }else{
                                            $moni_province = $province[$member['province']];
                                        }
                                    }else{
                                        $moni_province = $province[$member['province']];
                                    }
                                }
                            }
                        }
                        if(!empty($member['id'])){
                            $temp_log = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_login_log') . " WHERE weid=" . $weid . " AND mid=" . $mid . " ORDER BY createtime DESC LIMIT 1 ");
                            $today = strtotime("today");
                            if($temp_log['createtime'] < $today){
                                $data = array('weid' => $weid, 'mid' => $mid, 'createtime' => TIMESTAMP,);
                                pdo_insert('jy_ppp_login_log', $data);
                                $this -> sendxuni($member);
                                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('zhaohu', array('foo' => 'detail')) . "';
				</script>";
                                message($today);
                                exit;
                            }else{
                                $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                                $op = $_GPC['op'];
                                if($op == 'zhaohu'){
                                    echo 1;
                                    exit;
                                }elseif ($op == 'pic'){
                                    $id = $_GPC['id'];
                                    if(empty($id)){
                                        echo 3;
                                        exit;
                                    }else{
                                        $text = "你好，可以上传几张你的照片吗？想多了解你一些。";
                                        $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND sendid=" . $id . " AND content='" . $text . "'");
                                        if(empty($temp)){
                                            $data4 = array('weid' => $weid, 'mid' => $id, 'sendid' => $mid, 'content' => $text, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_xinxi", $data4);
                                            $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                            if($xuni_temp['type'] == 3){
                                                $this -> sendxuni($member, $xuni_temp);
                                            }
                                            echo 1;
                                            exit;
                                        }else{
                                            echo 2;
                                            exit;
                                        }
                                    }
                                }elseif ($op == 'attent'){
                                    $id = $_GPC['id'];
                                    if(empty($id)){
                                        echo 3;
                                        exit;
                                    }else{
                                        $attent = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_attent') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND attentid=" . $id);
                                        if(empty($attent)){
                                            $data = array('weid' => $weid, 'mid' => $mid, 'attentid' => $id, 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_attent", $data);
                                            echo 1;
                                            exit;
                                        }else{
                                            pdo_delete("jy_ppp_attent", array('weid' => $weid, 'mid' => $mid, 'attentid' => $id));
                                            echo 2;
                                            exit;
                                        }
                                    }
                                }elseif ($op == 'black'){
                                    $id = $_GPC['id'];
                                    if(empty($id)){
                                        echo 3;
                                        exit;
                                    }else{
                                        $attent = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_black') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND blackid=" . $id);
                                        if(empty($attent)){
                                            $data = array('weid' => $weid, 'mid' => $mid, 'blackid' => $id, 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_black", $data);
                                            echo 1;
                                            exit;
                                        }else{
                                            pdo_delete("jy_ppp_black", array('weid' => $weid, 'mid' => $mid, 'blackid' => $id));
                                            pdo_delete("jy_ppp_attent", array('weid' => $weid, 'mid' => $mid, 'attentid' => $id));
                                            echo 2;
                                            exit;
                                        }
                                    }
                                }else{
                                    $id = $_GPC['id'];
                                    if(empty($id)){
                                        if($member['sex'] == 1){
                                            $condition .= " AND sex=2 ";
                                            $condition2 .= " AND sex=2 ";
                                        }else{
                                            $condition .= " AND sex=1 ";
                                            $condition2 .= " AND sex=1 ";
                                        }
                                        $match = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_match') . " WHERE weid=" . $weid . " AND mid=" . $mid);
                                        if(!empty($match)){
                                            if($match['province'] > 0){
                                                $temp_province = $match['province'];
                                            }else{
                                                if(!empty($member['province'])){
                                                    $temp_province = $member['province'];
                                                }else{
                                                    $temp_province = 11;
                                                }
                                            }
                                            if(!empty($moni_province)){
                                                $condition .= " AND ( type=3 OR (type!=3 AND province= " . $temp_province . ")   ) ";
                                            }else{
                                                $condition .= " AND province= " . $temp_province;
                                            }
                                        }
                                        $tuijian = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . $condition . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_member') . " ) ORDER BY id LIMIT 1");
                                        if(!empty($tuijian)){
                                            $id = $tuijian['id'];
                                        }else{
                                            $tuijian = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . $condition2 . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_member') . " ) ORDER BY id LIMIT 1");
                                            if(!empty($tuijian)){
                                                $id = $tuijian['id'];
                                            }else{
                                                $tuijian = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . $condition2 . " LIMIT 200");
                                                $num = count($tuijian)-1;
                                                $ran = mt_rand(0, $num);
                                                $id = $tuijian[$ran]['id'];
                                            }
                                        }
                                    }
                                    if(empty($id)){
                                        echo "<script>
							window.location.href = '" . $this -> createMobileUrl('index') . "';
						</script>";
                                    }else{
                                        $detail = pdo_fetch("SELECT a.id,a.nicheng,a.avatar,a.beizhu,a.sex,a.brith,a.province,a.city,a.type as type2,a.baoyue,a.mobile,a.mobile_auth,a.card_auth,b.height,b.weight,b.blood,b.education,b.job,b.income,b.marriage,b.house,c.child,c.yidi,c.leixin,c.sex as sex2,c.fumu,c.meili,d.age as age2,d.height as height2,d.education as education2,d.income as income2,d.province as province2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid left join " . tablename('jy_ppp_desc') . " as c on a.id=c.mid left join " . tablename('jy_ppp_match') . " as d on a.id=d.mid WHERE a.weid=" . $weid . " AND a.id= " . $id);
                                        $thumb = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_thumb') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND ( type=1 OR type=2 )");
                                        $aihao = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_aihao') . " WHERE weid=" . $weid . " AND mid=" . $id . " LIMIT 3");
                                        $tezheng = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_tezheng') . " WHERE weid=" . $weid . " AND mid=" . $id . " LIMIT 3");
                                        if(!empty($aihao)){
                                            foreach ($aihao as $key => $value){
                                                $detail['aihao'] .= $value['aihao'] . ",";
                                            }
                                            $detail['aihao'] = substr($detail['aihao'], 0, -1);
                                        }
                                        if(!empty($tezheng)){
                                            foreach ($tezheng as $key => $value){
                                                $detail['tezheng'] .= $value['tezheng'] . ",";
                                            }
                                            $detail['tezheng'] = substr($detail['tezheng'], 0, -1);
                                        }
                                        $attent = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_attent') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND attentid=" . $id);
                                        $black = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_black') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND blackid=" . $id);
                                        if($member['sex'] == 1){
                                            if(empty($member['baoyue'])){
                                                $baoyue = 0;
                                            }else{
                                                $baoyue = $member['baoyue'] - time();
                                                if($baoyue <= 0){
                                                    $baoyue = 0;
                                                }else{
                                                    $baoyue = 1;
                                                }
                                            }
                                            if(!empty($baoyue)){
                                                $ltlx = 2;
                                            }else{
                                                $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_chat_doubi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND chatid=" . $id);
                                                if(empty($temp)){
                                                    $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND sendid=" . $id . " AND type=2 ");
                                                    if(!empty($temp)){
                                                        $ltlx = 3;
                                                    }else{
                                                        $today = strtotime('today');
                                                        $temp = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND sendid=" . $id . " AND type=0 AND createtime> " . $today);
                                                        if(empty($temp)){
                                                            $ltlx = 1;
                                                        }else{
                                                            if(count($temp) > 1){
                                                                $ltlx = 3;
                                                            }else{
                                                                $ltlx = 1;
                                                            }
                                                        }
                                                    }
                                                }else{
                                                    $ltlx = 2;
                                                }
                                            }
                                        }else{
                                            $duifang = pdo_fetch("SELECT baoyue FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                            if(empty($duifang['baoyue'])){
                                                $baoyue = 0;
                                            }else{
                                                $baoyue = $duifang['baoyue'] - time();
                                                if($baoyue <= 0){
                                                    $baoyue = 0;
                                                }else{
                                                    $baoyue = 1;
                                                }
                                            }
                                            if(!empty($baoyue)){
                                                $ltlx = 2;
                                            }else{
                                                $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_chat_doubi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND chatid=" . $id);
                                                if(!empty($temp)){
                                                    $ltlx = 2;
                                                }else{
                                                    $ltlx = 1;
                                                }
                                            }
                                        }
                                        if(!empty($sitem['youhuo_pay'])){
                                            if($detail['type2'] != 3){
                                                if(empty($detail['mobile'])){
                                                    unset($sitem['youhuo_pay']);
                                                }else{
                                                    $detail['mobile'] = $this -> real_mobile($detail['mobile']);
                                                }
                                            }else{
                                                $detail['mobile'] = $this -> rand_mobile($id);
                                                $detail['wechat'] = $this -> rand_wechat($id);
                                                $detail['qq'] = $this -> rand_qq($id);
                                            }
                                        }
                                        include $this -> template('detail');
                                    }
                                }
                            }
                        }else{
                            if($weixin == 1){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
                            }else{
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                            }
                        }
                    }
                    public function real_mobile($mobile){
                        $temp_mobile = substr($mobile, 0, 2);
                        $temp2_mobile = substr($mobile, 9, 2);
                        $mobile = $temp_mobile . '*******' . $temp2_mobile;
                        return $mobile;
                    }
                    public function rand_wechat($id){
                        $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                        $temp_one = $id % 26;
                        $wechat = $letters[$temp_one];
                        $temp_two = ($id * 5) % 36;
                        $wechat .= $letters[$temp_two];
                        $temp_th = $id % 9;
                        if($temp_th < 4){
                            $temp_th += 4;
                        }
                        for ($i = 0; $i < $temp_th; $i++){
                            $wechat .= '*';
                        }
                        $temp_forth = ($id * 7) % 36;
                        $wechat .= $letters[$temp_forth];
                        $temp_five = ($id * 11) % 36;
                        $wechat .= $letters[$temp_five];
                        return $wechat;
                    }
                    public function rand_qq($id){
                        $letters = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
                        $temp_one = $id % 9;
                        $wechat = $letters[$temp_one];
                        $temp_two = ($id * 3) % 9;
                        $wechat .= $letters[$temp_two];
                        $temp_th = $id % 6;
                        if($temp_th < 4){
                            $temp_th += 3;
                        }
                        for ($i = 0; $i < $temp_th; $i++){
                            $wechat .= '*';
                        }
                        $temp_forth = ($id * 7) % 9;
                        $wechat .= $letters[$temp_forth];
                        $temp_five = ($id * 11) % 9;
                        $wechat .= $letters[$temp_five];
                        return $wechat;
                    }
                    public function rand_mobile($id){
                        $letter = array('13', '15', '17', '18');
                        $letters = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                        $temp_one = $id % 4;
                        $wechat = $letter[$temp_one];
                        for ($i = 0; $i < 7; $i++){
                            $wechat .= '*';
                        }
                        $temp_forth = ($id * 7) % 10;
                        $wechat .= $letters[$temp_forth];
                        $temp_five = ($id * 11) % 10;
                        $wechat .= $letters[$temp_five];
                        return $wechat;
                    }
                    public function doMobileUserthumb(){
                        include_once 'inc/mobile/userthumb.php';
                    }
                    public function doMobileChat(){
                        global $_W, $_GPC;
                        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                            $weixin = 0;
                            $weid = $_GPC['i'];
                            $mid = $_SESSION['mid'];
                            if(!empty($mid)){
                                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                            }
                        }else{
                            $weixin = 1;
                            $weid = $_W['uniacid'];
                            $from_user = $_SESSION['jy_openid'];
                            if(empty($from_user)){
                                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'chat', 'id' => $_GPC['id'])) . "';
				</script>";
                            }else{
                                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                    unset($uid);
                                }else{
                                    $uid = $member_temp['uid'];
                                    unset($member_temp);
                                }
                                if(empty($uid)){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'chat', 'id' => $_GPC['id'])) . "';
					</script>";
                                }else{
                                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                                    $mid = $member['id'];
                                }
                            }
                        }
                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                        if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                            $this -> unzhuce();
                            $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                            $mid = $member['id'];
                        }
                        include "../addons/jy_ppp/inc/mobile/area.php";
                        if(!empty($sitem['moni'])){
                            if(empty($_SESSION['address']) || $_SESSION['address_time'] < time()){
                                $op = $_GPC['op'];
                                $op_foo = 'mail';
                                $moni = 1;
                                if($weixin == 1){
                                    include $this -> template('addtest');
                                    exit;
                                }
                            }else{
                                $moni_province = $_SESSION['moni_province'];
                                $moni_city = $_SESSION['moni_city'];
                                if(empty($sitem['user_addr'])){
                                    $moni_province .= $moni_city;
                                }elseif ($sitem['user_addr'] == 2){
                                    $moni_province = $moni_city;
                                }
                                if(empty($moni_province)){
                                    if(empty($member['province'])){
                                        $member['province'] = 11;
                                    }
                                    $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                                    if(empty($sitem['user_addr'])){
                                        $moni_province = $province[$member['province']];
                                        if(!empty($member['city'])){
                                            $moni_province .= $sub_array[$member['province']][$member['city']];
                                        }
                                    }elseif ($sitem['user_addr'] == 2){
                                        if(!empty($member['city'])){
                                            $moni_province = $sub_array[$member['province']][$member['city']];
                                        }else{
                                            $moni_province = $province[$member['province']];
                                        }
                                    }else{
                                        $moni_province = $province[$member['province']];
                                    }
                                }
                            }
                        }
                        if(!empty($member['id'])){
                            $province = array('11' => '北京市', '12' => '天津市', '13' => '河北省', '14' => '山西省', '15' => '内蒙古', '21' => '辽宁省', '22' => '吉林省', '23' => '黑龙江省', '31' => '上海市', '32' => '江苏省', '33' => '浙江省', '34' => '安徽省', '35' => '福建省', '36' => '江西省', '37' => '山东省', '41' => '河南省', '42' => '湖北省', '43' => '湖南省', '44' => '广东省', '45' => '广西', '46' => '海南省', '50' => '重庆市', '51' => '四川省', '52' => '贵州省', '53' => '云南省', '54' => '西藏', '61' => '陕西省', '62' => '甘肃省', '63' => '青海省', '64' => '宁夏', '65' => '新疆', '71' => '台湾省', '81' => '香港', '82' => '澳门');
                            $op = $_GPC['op'];
                            if($op == 'zhaohu'){
                                $id = $_GPC['id'];
                                if(empty($id)){
                                    echo 3;
                                    exit;
                                }
                                $today = strtotime("today");
                                $temp = pdo_fetch("SELECT zhaohuid,createtime FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND mid=" . $id . " AND type=0 ORDER BY createtime DESC LIMIT 1 ");
                                if(!empty($temp) && $member['sex'] == 2 && !empty($temp['zhaohuid'])){
                                    $temp_huifu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $id . " AND mid=" . $mid . " AND type=0 AND zhaohuid=" . $temp['zhaohuid'] . " AND createtime>" . $temp['createtime']);
                                    if(!empty($temp_huifu)){
                                        unset($temp['createtime']);
                                    }
                                }
                                if(!empty($temp) && $temp['createtime'] > $today){
                                    echo 2;
                                }else{
                                    $data = array('weid' => $weid, 'mid' => $id, 'sendid' => $mid, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                                    if($member['sex'] == 2){
                                        $wenti = pdo_fetch("SELECT id,name FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND parentid=0 AND enabled=1 " . " AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM " . tablename('jy_ppp_zhaohu') . " ) ORDER BY id LIMIT 1");
                                        $data['content'] = $wenti['name'];
                                        $data['zhaohuid'] = $wenti['id'];
                                        if(empty($data['content'])){
                                            $data['content'] = "hello,您好！很高兴认识你，可以交个朋友么？";
                                        }
                                    }else{
                                        $data['content'] = "你觉得她很赞，和她打了个招呼";
                                    }
                                    pdo_insert("jy_ppp_xinxi", $data);
                                    $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    if($xuni_temp['type'] == 3){
                                        $this -> sendxuni($member, $xuni_temp);
                                    }
                                    echo 1;
                                    $kefu_member = pdo_fetch("SELECT type,from_user,nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    $send_member = pdo_fetch("SELECT nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                                    if($kefu_member['type'] != 3 && !empty($kefu_member['from_user'])){
                                        $send_kefu_tf = false;
                                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                        if($sitem['jiange'] > 0){
                                            $temp_kefu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " ORDER BY createtime DESC LIMIT 1 ");
                                            $temp_jg_time = time() - $temp_kefu['createtime'];
                                            if($temp_jg_time > $sitem['jiange'] * 60){
                                                $today = strtotime('today');
                                                $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                                if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                    $send_kefu_tf = true;
                                                }
                                            }
                                        }else{
                                            $today = strtotime('today');
                                            $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                            if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                $send_kefu_tf = true;
                                            }
                                        }
                                        if($send_kefu_tf == true){
                                            $text2 = "附近的'" . $send_member['nicheng'] . "'给你发了一封表白信";
                                            $text = urlencode($text2);
                                            $desc2 = "点击下方【信箱】按钮查看所有来信";
                                            $desc = urlencode($desc2);
                                            $pic2 = $this -> mailui();
                                            $pic = urlencode($pic2);
                                            $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2);
                                            $url = urlencode($url2);
                                            $access_token = WeAccount :: token();
                                            $data = array("touser" => $kefu_member['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $desc, "url" => $url, 'picurl' => $pic)))));
                                            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                                            load() -> func('communication');
                                            $response = ihttp_request($url, urldecode(json_encode($data)));
                                            $errcode = json_decode($response['content'], true);
                                            $data3 = array('weid' => $weid, 'mid' => $id, 'type' => 'news', 'content' => $text2, 'desc' => $desc2, 'url' => $url2, 'picurl' => $pic2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_kefu", $data3);
                                        }
                                    }
                                    exit;
                                }
                            }elseif ($op == 'huifu'){
                                $id = $_GPC['id'];
                                if($member['sex'] == 2){
                                    echo 3;
                                    exit;
                                }else{
                                    if(empty($member['baoyue'])){
                                        $baoyue = 0;
                                    }else{
                                        $baoyue = $member['baoyue'] - time();
                                        if($baoyue <= 0){
                                            $baoyue = 0;
                                        }else{
                                            $baoyue = 1;
                                        }
                                    }
                                    if(!empty($baoyue)){
                                        echo 3;
                                        exit;
                                    }else{
                                        $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_chat_doubi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND chatid=" . $id);
                                        if(!empty($temp)){
                                            echo 3;
                                            exit;
                                        }else{
                                            if(empty($member['credit'])){
                                                echo 1;
                                                exit;
                                            }else{
                                                echo 2;
                                                exit;
                                            }
                                        }
                                    }
                                }
                            }elseif ($op == 'ans'){
                                $id = $_GPC['id'];
                                $zhaohuid = $_GPC['wtid'];
                                $ansid = $_GPC['ansid'];
                                if(empty($zhaohuid) || empty($ansid) || empty($id)){
                                    echo json_encode(array('status' => 2));
                                    exit;
                                }else{
                                    $ans = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND id=" . $ansid);
                                    $html = $ans['description'];
                                    if(empty($html)){
                                        $html = $ans['name'];
                                    }
                                    $data = array('weid' => $weid, 'mid' => $id, 'sendid' => $mid, 'content' => $html, 'zhaohuid' => $zhaohuid, 'huifuid' => $ansid, 'type' => 0, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                                    pdo_insert("jy_ppp_xinxi", $data);
                                    $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    if($xuni_temp['type'] == 3){
                                        $this -> sendxuni($member, $xuni_temp);
                                    }
                                    echo json_encode(array('status' => 1, 'time' => date('Y-m-d G:i'), 'data' => $html));
                                    $kefu_member = pdo_fetch("SELECT type,from_user,nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    $send_member = pdo_fetch("SELECT nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                                    if($kefu_member['type'] != 3 && !empty($kefu_member['from_user'])){
                                        $send_kefu_tf = false;
                                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                        if($sitem['jiange'] > 0){
                                            $temp_kefu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " ORDER BY createtime DESC LIMIT 1 ");
                                            $temp_jg_time = time() - $temp_kefu['createtime'];
                                            if($temp_jg_time > $sitem['jiange'] * 60){
                                                $today = strtotime('today');
                                                $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                                if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                    $send_kefu_tf = true;
                                                }
                                            }
                                        }else{
                                            $today = strtotime('today');
                                            $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                            if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                $send_kefu_tf = true;
                                            }
                                        }
                                        if($send_kefu_tf == true){
                                            $text2 = "附近的'" . $send_member['nicheng'] . "'给你发了一封表白信";
                                            $text = urlencode($text2);
                                            $desc2 = "点击下方【信箱】按钮查看所有来信";
                                            $desc = urlencode($desc2);
                                            $pic2 = $this -> mailui();
                                            $pic = urlencode($pic2);
                                            $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2);
                                            $url = urlencode($url2);
                                            $access_token = WeAccount :: token();
                                            $data = array("touser" => $kefu_member['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $desc, "url" => $url, 'picurl' => $pic)))));
                                            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                                            load() -> func('communication');
                                            $response = ihttp_request($url, urldecode(json_encode($data)));
                                            $errcode = json_decode($response['content'], true);
                                            $data3 = array('weid' => $weid, 'mid' => $id, 'type' => 'news', 'content' => $text2, 'desc' => $desc2, 'url' => $url2, 'picurl' => $pic2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_kefu", $data3);
                                        }
                                    }
                                    exit;
                                }
                            }elseif ($op == 'ans2'){
                                $id = $_GPC['id'];
                                $str = $_GPC['str'];
                                if(empty($id)){
                                    echo json_encode(array('status' => 2));
                                    exit;
                                }else{
                                    $data = array('weid' => $weid, 'mid' => $id, 'sendid' => $mid, 'content' => $str, 'type' => 2, 'yuedu' => 0, 'createtime' => TIMESTAMP,);
                                    pdo_insert("jy_ppp_xinxi", $data);
                                    $xuni_temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    if($xuni_temp['type'] == 3){
                                        $this -> sendxuni($member, $xuni_temp);
                                    }
                                    echo json_encode(array('status' => 1, 'time' => date('Y-m-d G:i')));
                                    $kefu_member = pdo_fetch("SELECT type,from_user,nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    $send_member = pdo_fetch("SELECT nicheng FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                                    if($kefu_member['type'] != 3 && !empty($kefu_member['from_user'])){
                                        $send_kefu_tf = false;
                                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                        if($sitem['jiange'] > 0){
                                            $temp_kefu = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " ORDER BY createtime DESC LIMIT 1 ");
                                            $temp_jg_time = time() - $temp_kefu['createtime'];
                                            if($temp_jg_time > $sitem['jiange'] * 60){
                                                $today = strtotime('today');
                                                $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                                if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                    $send_kefu_tf = true;
                                                }
                                            }
                                        }else{
                                            $today = strtotime('today');
                                            $temp_kefu_num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('jy_ppp_kefu') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND createtime>" . $today);
                                            if(!empty($sitem['shangxian']) && $temp_kefu_num < $sitem['shangxian']){
                                                $send_kefu_tf = true;
                                            }
                                        }
                                        if($send_kefu_tf == true){
                                            $text2 = "附近的'" . $send_member['nicheng'] . "'给你发了一封表白信";
                                            $text = urlencode($text2);
                                            $desc2 = "点击下方【信箱】按钮查看所有来信";
                                            $desc = urlencode($desc2);
                                            $pic2 = $this -> mailui();
                                            $pic = urlencode($pic2);
                                            $url2 = $_W['siteroot'] . "app/" . substr($this -> createMobileUrl('mail'), 2);
                                            $url = urlencode($url2);
                                            $access_token = WeAccount :: token();
                                            $data = array("touser" => $kefu_member['from_user'], "msgtype" => "news", "news" => array("articles" => array(0 => (array("title" => $text, "description" => $desc, "url" => $url, 'picurl' => $pic)))));
                                            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
                                            load() -> func('communication');
                                            $response = ihttp_request($url, urldecode(json_encode($data)));
                                            $errcode = json_decode($response['content'], true);
                                            $data3 = array('weid' => $weid, 'mid' => $id, 'type' => 'news', 'content' => $text2, 'desc' => $desc2, 'url' => $url2, 'picurl' => $pic2, 'status' => $errcode['errcode'], 'createtime' => TIMESTAMP,);
                                            pdo_insert("jy_ppp_kefu", $data3);
                                        }
                                    }
                                    exit;
                                }
                            }elseif ($op == 'more'){
                                $id = $_GPC['id'];
                                $lastid = $_GPC['lastid'];
                                if(empty($lastid)){
                                    echo json_encode(array('status' => 3));
                                    exit;
                                }
                                $temp = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND id=" . $lastid);
                                if(empty($temp['createtime'])){
                                    echo json_encode(array('status' => 4));
                                    exit;
                                }
                                if(empty($temp['sendid'])){
                                    $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                    if(!empty($sitem['adminthumb'])){
                                        $avatar2 = tomedia($sitem['adminthumb']);
                                    }else{
                                        $avatar2 = "../addons/jy_ppp/images/adminthumb.png";
                                    }
                                }else{
                                    $send = pdo_fetch("SELECT sex,avatar FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                    if(!empty($send['avatar'])){
                                        $avatar2 = tomedia($send['avatar']);
                                    }else{
                                        if($send['sex'] == 1){
                                            $avatar2 = "../addons/jy_ppp/images/boy.png";
                                        }else{
                                            $avatar2 = "../addons/jy_ppp/images/girl.png";
                                        }
                                    }
                                }
                                $list = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND (( mid=" . $mid . " AND sendid=" . $id . " ) OR ( mid=" . $id . " AND sendid=" . $mid . " ) ) AND createtime<" . $temp['createtime'] . " ORDER BY createtime DESC LIMIT 3");
                                if(count($list) == 2){
                                    $temp_msg = $list[0];
                                    $list[0] = $list[1];
                                    $list[1] = $temp_msg;
                                }else if(count($list) == 3){
                                    $temp_msg = $list[0];
                                    $list[0] = $list[2];
                                    $list[2] = $temp_msg;
                                }
                                $html = '';
                                if(!empty($member['avatar'])){
                                    $avatar = tomedia($member['avatar']);
                                }else{
                                    if($member['sex'] == 1){
                                        $avatar = "../addons/jy_ppp/images/boy.png";
                                    }else{
                                        $avatar = "../addons/jy_ppp/images/girl.png";
                                    }
                                }
                                if(!empty($list)){
                                    foreach ($list as $key => $l){
                                        $html .= '<li class="time" data-id="' . $l['id'] . '">' . date('Y-m-d G:i', $l['createtime']) . '</li>';
                                        if($l['sendid'] == $mid){
                                            $html .= '<li class="me disbox-hor dis_block"><div class="f_right" style="position: relative;"><img src="' . $avatar . '"></div><div class="disbox-flex f_right no_padding"><p class="talk">' . $l['content'] . '</p></div><div class="f_right"></div></li>';
                                        }else{
                                            $html .= '<li class="you disbox-hor dis_block "><div class="f_left" style="position: relative;"><img src="' . $avatar2 . '"></div><div class="disbox-flex f_left no_padding "><p class="talk">' . $l['content'] . '</p></div><div class="f_left"></div></li>';
                                        }
                                    }
                                }
                                if(count($list) < 3){
                                    echo json_encode(array('status' => 2, 'log' => $html));
                                    exit;
                                }else{
                                    echo json_encode(array('status' => 1, 'log' => $html));
                                    exit;
                                }
                            }elseif ($op == 'next'){
                                $next = pdo_fetch("SELECT sendid FROM " . tablename('jy_ppp_xinxi') . "  WHERE weid=" . $weid . " AND mid=" . $mid . " GROUP BY sendid ORDER BY yuedu ASC,createtime DESC LIMIT 1 ");
                                if(!empty($next['sendid'])){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('chat', array('id' => $next['sendid'])) . "';
					</script>";
                                }else{
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('mail') . "';
					</script>";
                                }
                            }else{
                                $id = $_GPC['id'];
                                $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                                $baoyue_cate = pdo_fetchall ("SELECT * FROM " . tablename ('jy_ppp_price') . " WHERE weid = " . $weid . " AND log=2 ORDER BY displayorder DESC,id ASC");
                                if(empty($id)){
                                    $list = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND sendid=0 AND type=3 ORDER BY createtime DESC LIMIT 3");
                                    pdo_update("jy_ppp_xinxi", array('yuedu' => 1, 'yuedutime' => TIMESTAMP), array('weid' => $weid, 'mid' => $mid, 'sendid' => 0, 'type' => 3));
                                    include $this -> template('chat');
                                }else{
                                    $detail = pdo_fetch("SELECT a.*,b.height,b.income,b.marriage,a.type as type2 FROM " . tablename('jy_ppp_member') . " as a left join " . tablename('jy_ppp_basic') . " as b on a.id=b.mid  WHERE a.weid=" . $weid . " AND a.id= " . $id);
                                    if($member['sex'] == 1){
                                        if(empty($member['baoyue'])){
                                            $baoyue = 0;
                                        }else{
                                            $baoyue = $member['baoyue'] - time();
                                            if($baoyue <= 0){
                                                $baoyue = 0;
                                            }else{
                                                $baoyue = 1;
                                            }
                                        }
                                        if(!empty($baoyue)){
                                            $ltlx = 2;
                                        }else{
                                            $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_chat_doubi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND chatid=" . $id);
                                            if(empty($temp)){
                                                $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND sendid=" . $id . " AND type=2 ");
                                                if(!empty($temp)){
                                                    $ltlx = 3;
                                                }else{
                                                    $today = strtotime('today');
                                                    $temp = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND sendid=" . $id . " AND type=0 AND createtime> " . $today);
                                                    if(empty($temp)){
                                                        $ltlx = 1;
                                                    }else{
                                                        if(count($temp) > 1){
                                                            $ltlx = 3;
                                                        }else{
                                                            $ltlx = 1;
                                                        }
                                                    }
                                                }
                                            }else{
                                                $ltlx = 2;
                                            }
                                        }
                                    }else{
                                        $duifang = pdo_fetch("SELECT baoyue FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $id);
                                        if(empty($duifang['baoyue'])){
                                            $baoyue = 0;
                                        }else{
                                            $baoyue = $duifang['baoyue'] - time();
                                            if($baoyue <= 0){
                                                $baoyue = 0;
                                            }else{
                                                $baoyue = 1;
                                            }
                                        }
                                        if(!empty($baoyue)){
                                            $ltlx = 2;
                                        }else{
                                            $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_chat_doubi') . " WHERE weid=" . $weid . " AND mid=" . $id . " AND chatid=" . $mid);
                                            if(!empty($temp)){
                                                $ltlx = 2;
                                            }else{
                                                $ltlx = 1;
                                            }
                                        }
                                    }
                                    if(!empty($member['avatar'])){
                                        $avatar = tomedia($member['avatar']);
                                    }else{
                                        if($member['sex'] == 1){
                                            $avatar = "../addons/jy_ppp/images/boy.png";
                                        }else{
                                            $avatar = "../addons/jy_ppp/images/girl.png";
                                        }
                                    }
                                    $list = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND ( ( mid=" . $mid . " AND sendid=" . $id . " ) OR ( mid=" . $id . " AND sendid=" . $mid . " ) ) ORDER BY createtime DESC LIMIT 3");
                                    if($member['sex'] == 1 && empty($list[0]['type']) && !empty($list[0]['zhaohuid']) && empty($list[0]['huifuid'])){
                                        $zhaohuid = $list[0]['zhaohuid'];
                                        $wt = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_zhaohu') . " WHERE weid=" . $weid . " AND parentid=" . $list[0]['zhaohuid'] . " AND enabled=1");
                                    }
                                    if(count($list) == 2){
                                        $temp_msg = $list[0];
                                        $list[0] = $list[1];
                                        $list[1] = $temp_msg;
                                    }else if(count($list) == 3){
                                        $temp_msg = $list[0];
                                        $list[0] = $list[2];
                                        $list[2] = $temp_msg;
                                    }
                                    pdo_update("jy_ppp_xinxi", array('yuedu' => 1, 'yuedutime' => TIMESTAMP), array('weid' => $weid, 'mid' => $mid, 'sendid' => $id));
                                    if($ltlx == 3){
                                        $temp = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_xinxi') . " WHERE weid=" . $weid . " AND sendid=" . $mid . " AND mid=" . $id . " and type=2 and createtime>='" . strtotime('today') . "'");
                                        if(empty($temp)){
                                            $ltlx = 2;
                                            $first_chat = 1;
                                        }
                                    }
                                    include $this -> template('chat');
                                }
                            }
                        }else{
                            if($weixin == 1){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
                            }else{
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                            }
                        }
                    }
                    public function doMobileReport(){
                        include_once 'inc/mobile/report.php';
                    }
                    public function doMobileCz(){
                        include_once 'inc/mobile/cz.php';
                    }
                    public function doMobileXhdoubi(){
                        include_once 'inc/mobile/xhdoubi.php';
                    }
                    public function doMobileVisit(){
                        include_once 'inc/mobile/visit.php';
                    }
                    public function doMobileLianxi(){
                        include_once 'inc/mobile/lianxi.php';
                    }
                    public function doMobileMylove(){
                        include_once 'inc/mobile/mylove.php';
                    }
                    public function doMobileLoveme(){
                        include_once 'inc/mobile/loveme.php';
                    }
                    public function doMobileMyblack(){
                        include_once 'inc/mobile/myblack.php';
                    }
                    public function doMobileSetting(){
                        include_once 'inc/mobile/setting.php';
                    }
                    public function doMobileAccount(){
                        include_once 'inc/mobile/account.php';
                    }
                    public function doMobileFeedback(){
                        include_once 'inc/mobile/feedback.php';
                    }
                    public function doMobileFdbk_s(){
                        include_once 'inc/mobile/fdbk_s.php';
                    }
                    public function doMobileBeizhu(){
                        include_once 'inc/mobile/beizhu.php';
                    }
                    public function doMobileMobile(){
                        include_once 'inc/mobile/mobile.php';
                    }
                    public function doMobileIdcard(){
                        include_once 'inc/mobile/idcard.php';
                    }
                    public function doMobileRenzheng(){
                        include_once 'inc/mobile/renzheng.php';
                    }
                    public function doMobileUpload(){
                        global $_W, $_GPC;
                        $xuniid = intval($_GPC['xuniid']);
                        if(empty($xuniid)){
                            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                                $weixin = 0;
                                $weid = $_GPC['i'];
                                $mid = $_SESSION['mid'];
                                if(!empty($mid)){
                                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                                }
                            }else{
                                $weixin = 1;
                                $weid = $_W['uniacid'];
                                $from_user = $_SESSION['jy_openid'];
                                if(empty($from_user)){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'upload')) . "';
					</script>";
                                }else{
                                    $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                    if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                        unset($uid);
                                    }else{
                                        $uid = $member_temp['uid'];
                                        unset($member_temp);
                                    }
                                    if(empty($uid)){
                                        echo "<script>
							window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'upload')) . "';
						</script>";
                                    }else{
                                        $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                                        $mid = $member['id'];
                                    }
                                }
                            }
                        }else{
                            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                                $weixin = 0;
                                $weid = $_GPC['i'];
                                $dyid = $_SESSION['dyid'];
                                if(!empty($dyid)){
                                    $dianyuan = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_dianyuan') . " WHERE weid=" . $weid . " AND id=" . $dyid);
                                }
                            }else{
                                $weixin = 1;
                                $weid = $_W['uniacid'];
                                $from_user = $_SESSION['jy_openid'];
                                if(empty($from_user)){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'upload')) . "';
					</script>";
                                }else{
                                    $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                    if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                        unset($uid);
                                    }else{
                                        $uid = $member_temp['uid'];
                                        unset($member_temp);
                                    }
                                    if(empty($uid)){
                                        echo "<script>
							window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'upload')) . "';
						</script>";
                                    }else{
                                        $dianyuan = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_dianyuan') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                                        $dyid = $dianyuan['id'];
                                    }
                                }
                            }
                            if(empty($dianyuan)){
                                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('dy_login') . "';
				</script>";
                            }else{
                                $xuni_qx = pdo_fetch("SELECT id FROM " . tablename('jy_ppp_xuni_member') . " WHERE weid=" . $weid . " AND mid=" . $xuniid . " AND dyid=" . $dyid);
                                if(empty($xuni_qx)){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('dy_user') . "';
					</script>";
                                }else{
                                    $mid = $xuniid;
                                    $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid);
                                }
                            }
                        }
                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                        if(empty($member) && $weixin == 1 && !empty($sitem['unzhuce'])){
                            $this -> unzhuce();
                            $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND from_user='" . $from_user . "' AND status=1");
                            $mid = $member['id'];
                        }
                        if(!empty($member)){
                            $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                            if ($_POST ['status']){
                                echo "SUCCESS";
                                echo $_POST ['filedata'];
                                load () -> func ('file');
                                preg_match ("/data:image\/(.*?);base64/", $_POST ['filedata'], $res);
                                $ext = $res [1];
                                echo $ext;
                                $data = preg_replace ("/data:image\/(.*);base64,/", "", $_POST ['filedata']);
                                echo $data;
                                $uniacid = intval ($_W ['uniacid']);
                                $path = "images/{$uniacid}/" . date ('Y/m/');
                                mkdirs (ATTACHMENT_ROOT . '/' . $path);
                                $filename = file_random_name (ATTACHMENT_ROOT . '/' . $path, $ext);
                                file_put_contents (ATTACHMENT_ROOT . '/' . $path . $filename, base64_decode ($data));
                                $result['path'] = $path . $filename;
                                if(!empty($sitem['thumb'])){
                                    pdo_insert('jy_ppp_thumb', array ('weid' => $weid, 'mid' => $mid, 'thumb' => $result ['path'], 'type' => 0, 'createtime' => TIMESTAMP));
                                }else{
                                    pdo_update("jy_ppp_member", array('avatar' => $result ['path']), array('id' => $mid));
                                    pdo_insert('jy_ppp_thumb', array ('weid' => $weid, 'mid' => $mid, 'thumb' => $result ['path'], 'type' => 1, 'createtime' => TIMESTAMP));
                                }
                                $thumb_id = pdo_insertid();
                                $href = $_W['siteroot'] . 'app/' . substr($this -> createMobileUrl('mythumb', array('id' => $thumb_id)), 2);
                                echo "<script>parent.iframecallback('" . tomedia($path . $filename) . "','" . $href . "')</script>";
                                exit ();
                            }else{
                                $thumb = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_thumb') . " WHERE weid=" . $weid . " AND mid=" . $mid . " AND type!=4 ORDER BY id DESC ");
                            }
                            include $this -> template('upload');
                        }else{
                            if($weixin == 1){
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('zhuce') . "';
					</script>";
                            }else{
                                echo "<script>
						window.location.href = '" . $this -> createMobileUrl('login') . "';
					</script>";
                            }
                        }
                    }
                    public function doMobileMythumb(){
                        include_once 'inc/mobile/mythumb.php';
                    }
                    public function doMobileTp_notice(){
                        include_once 'inc/mobile/tp_notice.php';
                    }
                    public function doMobileBasic(){
                        include_once 'inc/mobile/basic.php';
                    }
                    public function doMobileDesc(){
                        include_once 'inc/mobile/desc.php';
                    }
                    public function doMobileMatch(){
                        include_once 'inc/mobile/match.php';
                    }
                    public function doMobileMianrao(){
                        include_once 'inc/mobile/mianrao.php';
                    }
                    public function doMobileVip(){
                        include_once 'inc/mobile/vip.php';
                    }
                    public function doMobileDoubi(){
                        include_once 'inc/mobile/doubi.php';
                    }
                    public function doMobileBaoyue(){
                        include_once 'inc/mobile/baoyue.php';
                    }
                    public function doMobileShouxin(){
                        include_once 'inc/mobile/shouxin.php';
                    }
                    public function doMobileBind(){
                        global $_W, $_GPC;
                        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false){
                            $weixin = 0;
                            $weid = $_GPC['i'];
                            $mid = $_SESSION['mid'];
                            if(!empty($mid)){
                                $member = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_member') . " WHERE weid=" . $weid . " AND id=" . $mid . " AND status=1");
                            }
                        }else{
                            $weixin = 1;
                            $weid = $_W['uniacid'];
                            $from_user = $_SESSION['jy_openid'];
                            if(empty($from_user)){
                                echo "<script>
					window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'bind', 'id' => $_GPC['id'])) . "';
				</script>";
                            }else{
                                $member_temp = pdo_fetch("SELECT uid,nickname,follow FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                if(empty($member_temp['nickname']) || $member_temp['uid'] == 0){
                                    unset($uid);
                                }else{
                                    $uid = $member_temp['uid'];
                                    unset($member_temp);
                                }
                                if(empty($uid)){
                                    echo "<script>
						window.location.href = '" . $this -> createMobileUrl('userinfo', array('op' => 'bind', 'id' => $_GPC['id'])) . "';
					</script>";
                                }
                            }
                        }
                        $id = $_GPC['id'];
                        $sitem = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_setting') . " WHERE weid=" . $weid);
                        if($weixin == 1 && !empty($from_user) && !empty($uid) && !empty($id)){
                            pdo_update("jy_ppp_member", array('status' => 0, 'uid' => 0, 'from_user' => ''), array('from_user' => $from_user));
                            pdo_update("jy_ppp_member", array('status' => 1, 'uid' => $uid, 'from_user' => $from_user), array('id' => $id));
                            include $this -> template("bind");
                        }else{
                            include $this -> template("bind2");
                        }
                    }
                    public function doMobilePcpay(){
                        include_once 'inc/mobile/pcpay.php';
                    }
                    public function doMobilePay_c(){
                        include_once 'inc/mobile/pay_c.php';
                    }
                    public function doMobilePay(){
                        include_once 'inc/pay.php';
                    }
                    public function payResult($params){
                        include_once 'inc/pay2.php';
                    }
                    public function doMobileZhuce(){
                        include_once 'inc/mobile/zhuce.php';
                    }
                    public function doMobileZhuce2(){
                        include_once 'inc/mobile/zhuce2.php';
                    }
                    public function doMobileLogin(){
                        include_once 'inc/mobile/login.php';
                    }
                    public function doMobileLogout(){
                        include_once 'inc/mobile/logout.php';
                    }
                    public function doMobileRule(){
                        include_once 'inc/mobile/rule.php';
                    }
                    public function doMobileForget(){
                        include_once 'inc/mobile/forget.php';
                    }
                    public function doMobileHelp(){
                        include_once 'inc/mobile/help.php';
                    }
                    public function doMobileHelp_l(){
                        include_once 'inc/mobile/help_l.php';
                    }
                    public function doMobileHelp_d(){
                        include_once 'inc/mobile/help_d.php';
                    }
                    public function doMobileSafe(){
                        include_once 'inc/mobile/safe.php';
                    }
                    public function doMobileSafe_l(){
                        include_once 'inc/mobile/safe_l.php';
                    }
                    public function doMobileSafe_d(){
                        include_once 'inc/mobile/safe_d.php';
                    }
                    public function doMobileDy_login(){
                        include_once 'inc/mobile/dy_login.php';
                    }
                    public function doMobileDycenter(){
                        include_once 'inc/mobile/dycenter.php';
                    }
                    public function doMobileDy_user(){
                        include_once 'inc/mobile/dy_user.php';
                    }
                    public function doMobileDy_msg(){
                        include_once 'inc/mobile/dy_msg.php';
                    }
                    public function doMobileDy_chat(){
                        include_once 'inc/mobile/dy_chat.php';
                    }
                    public function doMobileDy_add(){
                        include_once 'inc/mobile/dy_add.php';
                    }
                    public function doMobileDy_man(){
                        include_once 'inc/mobile/dy_man.php';
                    }
                    public function doMobileDy_send(){
                        include_once 'inc/mobile/dy_send.php';
                    }
                    public function doMobileDy_newyh(){
                        include_once 'inc/mobile/dy_newyh.php';
                    }
                    public function doMobileDy_loginyh(){
                        include_once 'inc/mobile/dy_loginyh.php';
                    }
                    public function doMobileDy_kjmsg(){
                        include_once 'inc/mobile/dy_kjmsg.php';
                    }
                    public function doWebSetting(){
                        include_once 'inc/web/setting.php';
                    }
                    public function doWebHelp(){
                        include_once 'inc/web/help.php';
                    }
                    public function doWebSafe(){
                        include_once 'inc/web/safe.php';
                    }
                    public function doWebFeedback(){
                        include_once 'inc/web/feedback.php';
                    }
                    public function doWebThumb(){
                        include_once 'inc/web/thumb.php';
                    }
                    public function doWebXunithumb(){
                        include_once 'inc/web/xunithumb.php';
                    }
                    public function doWebZhaohu(){
                        include_once 'inc/web/zhaohu.php';
                    }
                    public function doMobileViapay(){
		        global $_W;
		        //header("location: ../payment/viapay/return.php"."?i=".$_W['uniacid']);
			//if(!empty($_GET['ps'])){
		        	$params = @json_decode(base64_decode($_GET['ps']),true);
			//}
		        //echo json_encode($params);
		        //die;
		        $this -> payResult($params);
                    }
                    public function doWebFenpei(){
                        include_once 'inc/web/fenpei.php';
                    }
                    public function doWebDianyuan(){
                        include_once 'inc/web/dianyuan.php';
                    }
                    public function doMobileDybind(){
                        include_once 'inc/mobile/dybind.php';
                    }
                    public function doWebUnbind(){
                        include_once 'inc/web/unbind.php';
                    }
                    public function doWebXuni(){
                        include_once 'inc/web/xuni.php';
                    }
                    public function doWebMember(){
                        include_once 'inc/web/member.php';
                    }
                    public function doWebPrice(){
                        include_once 'inc/web/price.php';
                    }
                    public function doWebCaiwu(){
                        include_once 'inc/web/caiwu.php';
                    }
                    public function doWebStat(){
                        include_once 'inc/web/stat.php';
                    }
                    public function doWebHf(){
                        include_once 'inc/web/hf.php';
                    }
                    public function doWebMsg(){
                        include_once 'inc/web/msg.php';
                    }
                    public function doWebXinxi(){
                        include_once 'inc/web/xinxi.php';
                    }
                    public function doWebXuni_pay(){
                        include_once 'inc/web/xuni_pay.php';
                    }
                    public function doWebQrcode(){
                        include_once 'inc/web/qrcode.php';
                    }
                    public function doWebnickname(){
                        include_once 'inc/web/nickname.php';
                    }
                    public function doWebmailui(){
                        include_once 'inc/web/mailui.php';
                    }
                    public function doWebimport(){
                        include_once 'inc/web/import.php';
                    }
                    public function doWebcoll_jobs(){
                        include_once 'inc/web/coll_jobs.php';
                    }
                    public function doWebone_coll(){
                        include_once 'inc/web/one_coll.php';
                    }
                    public function doMobileUserinfo(){
                        global $_W, $_GPC;
                        if (!empty($_W['openid']) && intval($_W['account']['level']) > 3){
                            $accObj = WeiXinAccount :: create($_W['account']);
                            $userinfo = $accObj -> fansQueryInfo($_W['openid']);
                        }
                        $op = $_GPC['op'];
                        $code = $_GET['code'];
                        if(empty($code)){
                            if($userinfo['subscribe'] == 0){
                                $url = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('userinfo', array('op' => $op, 'id' => $_GPC['id'], 'rid' => $_GPC['rid'], 'foo' => $_GPC['foo'], 'fdbk' => $_GPC['fdbk']));
                                $callback = urlencode($url);
                                $forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $_W['oauth_account']['key'] . '&redirect_uri=' . $callback . '&response_type=code&scope=snsapi_userinfo&state=jiuye#wechat_redirect';
                                header("Location: " . $forward);
                            }else{
                                $weid = $_W['uniacid'];
                                $from_user = $_W['openid'];
                                $fan_temp = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                if(!empty($userinfo) && !empty($userinfo['nickname'])){
                                    $userinfo['avatar'] = $userinfo['headimgurl'];
                                    unset($userinfo['headimgurl']);
                                    $_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
                                    $_SESSION['openid'] = $from_user;
                                    $_SESSION['jy_openid'] = $from_user;
                                    $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));
                                    $data = array('uniacid' => $weid, 'salt' => random(8), 'groupid' => $default_groupid, 'createtime' => TIMESTAMP, 'nickname' => stripslashes($userinfo['nickname']), 'avatar' => $userinfo['avatar'], 'gender' => $userinfo['sex'], 'nationality' => $userinfo['country'], 'resideprovince' => $userinfo['province'] . '省', 'residecity' => $userinfo['city'] . '市',);
                                    $data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
                                    if(empty($fan_temp)){
                                        pdo_insert('mc_members', $data);
                                        $uid = pdo_insertid();
                                    }else{
                                        pdo_update('mc_members' , $data , array('uid' => $fan_temp['uid']));
                                        $uid = $fan_temp['uid'];
                                    }
                                    $record = array('openid' => $_W['openid'], 'uid' => $uid, 'acid' => $_W['acid'], 'uniacid' => $weid, 'salt' => random(8), 'updatetime' => TIMESTAMP, 'nickname' => $userinfo['nickname'], 'follow' => $userinfo['subscribe'], 'followtime' => $userinfo['subscribe_time'], 'unfollowtime' => 0, 'tag' => base64_encode(iserializer($userinfo)));
                                    $record['uid'] = $uid;
                                    if(empty($fan_temp)){
                                        pdo_insert('mc_mapping_fans', $record);
                                    }else{
                                        pdo_update('mc_mapping_fans' , $record , array('fanid' => $fan_temp['fanid']));
                                    }
                                }
                            }
                        }else{
                            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $_W['oauth_account']['key'] . "&secret=" . $_W['oauth_account']['secret'] . "&code=" . $code . "&grant_type=authorization_code";
                            load() -> func('communication');
                            $response = ihttp_get($url);
                            $oauth = @json_decode($response['content'], true);
                            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauth['access_token']}&openid={$oauth['openid']}&lang=zh_CN";
                            $response = ihttp_get($url);
                            if (!is_error($response)){
                                $userinfo = array();
                                $userinfo = @json_decode($response['content'], true);
                                $userinfo['avatar'] = $userinfo['headimgurl'];
                                unset($userinfo['headimgurl']);
                                $_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
                                $_SESSION['openid'] = $oauth['openid'];
                                $_SESSION['jy_openid'] = $oauth['openid'];
                                $from_user = $oauth['openid'];
                                if(!empty($userinfo) && !empty($userinfo['nickname'])){
                                    $acid = $_W['oauth_account']['acid'];
                                    $weid = $_W['uniacid'];
                                    $fan_temp = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE openid='$from_user' AND uniacid=" . $weid);
                                    $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));
                                    $data = array('uniacid' => $weid, 'salt' => random(8), 'groupid' => $default_groupid, 'createtime' => TIMESTAMP, 'nickname' => stripslashes($userinfo['nickname']), 'avatar' => rtrim($userinfo['avatar'], '0') . 132, 'gender' => $userinfo['sex'], 'nationality' => $userinfo['country'], 'resideprovince' => $userinfo['province'] . '省', 'residecity' => $userinfo['city'] . '市',);
                                    $data['password'] = md5($oauth['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
                                    if(empty($fan_temp)){
                                        pdo_insert('mc_members', $data);
                                        $uid = pdo_insertid();
                                    }else{
                                        pdo_update('mc_members' , $data , array('uid' => $fan_temp['uid']));
                                        $uid = $fan_temp['uid'];
                                    }
                                    $record = array('openid' => $oauth['openid'], 'uid' => $uid, 'acid' => $acid, 'uniacid' => $weid, 'salt' => random(8), 'updatetime' => TIMESTAMP, 'nickname' => $userinfo['nickname'], 'follow' => $userinfo['subscribe'], 'followtime' => $userinfo['subscribe_time'], 'unfollowtime' => 0, 'tag' => base64_encode(iserializer($userinfo)));
                                    $record['uid'] = $uid;
                                    if(empty($fan_temp)){
                                        pdo_insert('mc_mapping_fans', $record);
                                    }else{
                                        $temp = pdo_update('mc_mapping_fans' , $record , array('fanid' => $fan_temp['fanid']));
                                    }
                                }
                            }else{
                                message('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);
                            }
                        }
                        echo "<script>
					window.location.href = '" . $this -> createMobileUrl($op, array('id' => $_GPC['id'], 'foo' => $_GPC['foo'], 'rid' => $_GPC['rid'], 'fdbk' => $_GPC['fdbk'])) . "';
				</script>";
                    }
                }
                
                ?>
