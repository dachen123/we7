<?php
global $_W, $_GPC;

$weid = $_W['uniacid'];
checklogin ();

//获取列表数据
function list_html($content,$site){
    $list_s = '<div id="content" class="sucai">';//开始标签
    $list_e = '<div class="digg" id="digg"><\/div>';//结束标签

    $l_s = '<p>';//单条记录开始
    $l_e = '<\/p>';//单条记录结束

    $int = preg_match("/{$list_s}(.*?){$list_e}/ius",$content,$h1);
    if(!$int){
        return false;
    }
    $h1 = $h1[1];

    $int = preg_match_all("/{$l_s}(.*?){$l_e}/ius",$h1,$h2);
    if(!$int){
        return false;
    }
    $h2 = $h2[1];
    $data = array();
    foreach($h2 as $key=>$value){
        $data[$key]['img'] = build_url(preg_img_src($value),$site);
    }
    return $data;

    //return $content;
}
//

function build_url($url,$site){
    if(false !== strpos($url,'http')){
        return $url;
    }
    return $site.$url;
}

function preg_img_src($img){
    preg_match('/<img.*?src="(.*?)".*?>/ius',$img,$array);
    return $array[1];
}

function mkdirs($path) {
    if (!is_dir($path)) {
        mkdirs(dirname($path));
        mkdir($path);
    }
    return is_dir($path);
}
function file_random_name($dir, $ext){
    do {
        $filename = random(30) . '.' . $ext;
    } while (file_exists($dir . $filename));

    return $filename;
}

function upload_img($img,$_W){
    $file = pathinfo($img);
    $open_ext = array('jpg','jpeg','gif','png');
    if(!in_array($file['extension'],$open_ext)){
        return false;
    }

    $rs = ihttp_get($img);
    if('OK'==$rs['status'] || !empty($rs['content'])){
        $content = $rs['content'];
        $path = 'images/'.$_W['uniacid'].'/' . date('Y/m/');
        $p = ATTACHMENT_ROOT . $path;
        mkdirs($p);
        $filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $file['extension']);
        $file_db_path = $path.$filename;
        $file_path = $p.$filename;
        //保存文件到本地服务器
        file_put_contents($file_path,$content);
        pdo_insert('core_attachment', array(

            'uniacid' => $_W['uniacid'],

            'uid' => $_W['uid'],

            'filename' => $file['basename'],

            'attachment' => $file_db_path,

            'type' => 1,

            'createtime' => TIMESTAMP,

        ));
        return $file_db_path;
    }else{
        return false;
    }



}


if(empty($_GPC ['url']) || empty($_GPC ['sex'])){
    message ( '采集参数错误！', $this->createWebUrl ( 'xunithumb', array ('op' => 'collection' ) ), 'error' );
    exit;
}
if(checksubmit ( 'submit' )){
    $sex = ! empty ( $_GPC ['sex'] ) ? $_GPC ['sex'] : 2;
    if(!empty($_GPC['img'])){
        $list_img = $_GPC['img'];
        $data = array();
        foreach($list_img as $value){
            $f = upload_img($value,$_W);
            if($f){
                $data[] = $f;
            }
        }
        if(!empty($data)){
            $llll=pdo_fetch("SELECT mid FROM ".tablename('jy_ppp_xunithumb')." ORDER BY mid DESC LIMIT 1 ");

            if(!empty($llll))

            {

                $mid=$llll['mid']+1;

            }

            else

            {

                $mid=1;

            }
            foreach($data as $k=>$v){
                 $d = array (

                'avatar' => 1,

                'sex' => $sex,

                'mid'=>$mid,

                'thumb' => $v,

                );
                if($k>0){
                    $d = array (

                        'avatar' => 0,

                        'sex' => $sex,

                        'mid'=>$mid,

                        'thumb' => $v,

                    );
                }else{
                    $d = array (

                        'avatar' => 1,

                        'sex' => $sex,

                        'mid'=>$mid,

                        'thumb' => $v,

                    );
                }

                pdo_insert('jy_ppp_xunithumb',$d);
            }
            message ( '导入成功！', $this->createWebUrl ( 'xunithumb', array ('op' => 'display' ) ), 'success' );


        }else{
            message ( '图片抓取失败', $this->createWebUrl ( 'xunithumb', array ('op' => 'display' ) ), 'error' );
            exit;
        }

    }else{
        message ( '未选择任何图片', $this->createWebUrl ( 'xunithumb', array ('op' => 'collection' ) ), 'error' );
        exit;
    }



}else{
    $url  = $_GPC ['url'];
    $path = parse_url($url);
    if(empty($path['host']) || empty($path['scheme'])){
        message ( 'url采集参数错误！'.$url, $this->createWebUrl ( 'xunithumb', array ('op' => 'collection' ) ), 'error' );
        exit;
    }
    $host = $path['scheme'].'://'.$path['host'];
    $sex = ! empty ( $_GPC ['sex'] ) ? $_GPC ['sex'] : 2;
    $rs = ihttp_get($url);
    if('OK'==$rs['status'] || !empty($rs['content'])){
        $content = iconv('gbk','UTF-8',$rs['content']);
        $list = list_html($content,$host);
    }
    include $this->template ( 'web/one_coll' );
}