<?php defined('IN_IA') or exit('Access Denied');?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title><?php  if(!empty($sitem['aname'])) { ?><?php  echo $sitem['aname'];?><?php  } else { ?>有缘网<?php  } ?></title>
<link href="../addons/jy_ppp/css/public_reset.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/public.css" rel="stylesheet" type="text/css"/>
<link href="../addons/jy_ppp/css/user_setting.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="top_blank">
</div>
<nav class="nav" style="position: absolute">
<h2>基本资料</h2>
<a class="left" onclick="history.go(-1)">
    <i class="le_trg"></i>返回
</a>
<div class="right">
    <span id="search_sure" class="seach_sure">确定</span>
</div>
</nav>
<div class="content">
    <ul class="habit-list">
        <li class="user_mession" style="height: auto; overflow: hidden;">
        <label class="left">昵称</label>
        <div class="right" style=" height:auto;">
            <input type="text" id="nickname" placeholder="<?php  echo $member['nicheng'];?>"/><br/>
            <span style="float:right;"> *禁止使用非法昵称 </span>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">生日</label>
        <div id="basic_birthday" class="right">
            <div class="select">
                <span class="value"><?php  echo $year;?>年</span>
                <i class="bot_trged"></i>
                <select class="value_select">
                    <?php  for ($i=1997; $i >1954 ; $i--) {
                        ?> <option value="<?php  echo $i;?>" <?php  if($year==$i) { ?> selected="selected" <?php  } ?>><?php  echo $i;?>年 </option><?php 
                    } ?>
                </select>
            </div>
            <div class="select">
                <span class="value"><?php  echo $month;?>月</span>
                <i class="bot_trged"></i>
                <select class="value_select">
                    <?php  for ($i=1; $i <13 ; $i++) {
                        ?> <option value="<?php  echo $i;?>" <?php  if($month==$i) { ?> selected="selected" <?php  } ?>><?php  echo $i;?>月 </option><?php 
                    } ?>
                </select>
            </div>
            <div class="select">
                <span class="value"><?php  echo $day;?>日</span>
                <i class="bot_trged"></i>
                <select class="value_select">
                    <?php  for ($i=1; $i <32 ; $i++) {
                        ?> <option value="<?php  echo $i;?>" <?php  if($day==$i) { ?> selected="selected" <?php  } ?>><?php  echo $i;?>日 </option><?php 
                    } ?>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">居住地</label>
        <div id="live" class="right">
            <div class="select">
                <span class="value" id="province">
                </span>
                <i class="bot_trged"></i>
                <select id="live_province" class="value_select">
                </select>
            </div>
            <div class="select fixed_width">
                <span class="value" id="city">
                </span>
                <i class="bot_trged"></i>
                <select id="live_city" class="value_select">
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">身高</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['height'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['height'];?>cm</span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="basic_height" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <?php  for ($i=140; $i <221 ; $i++) {
                        ?> <option value="<?php  echo $i;?>" <?php  if($basic['height']==$i) { ?> selected="selected" <?php  } ?>><?php  echo $i;?>cm </option><?php 
                    } ?>

                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">体重</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['weight'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['weight'];?>斤</span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="basic_weight" class="value_select">
                        <option value="0" style="display:none">请选择</option>
                        <?php  for ($i=60; $i <301 ; $i++) {
                            ?> <option value="<?php  echo $i;?>" <?php  if($basic['weight']==$i) { ?> selected="selected" <?php  } ?>><?php  echo $i;?>斤 </option><?php 
                        } ?>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">血型</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['blood'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['blood'];?></span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="bloodType" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <option value="A" <?php  if($basic['blood']=='A') { ?> selected="selected" <?php  } ?>>A</option>
                    <option value="B" <?php  if($basic['blood']=='B') { ?> selected="selected" <?php  } ?>>B</option>
                    <option value="AB" <?php  if($basic['blood']=='AB') { ?> selected="selected" <?php  } ?>>AB</option>
                    <option value="O" <?php  if($basic['blood']=='O') { ?> selected="selected" <?php  } ?>>O</option>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">学历</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['education'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['education'];?></span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="education" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <option value="初中及以下" <?php  if($basic['education']=='初中及以下') { ?> selected="selected" <?php  } ?>>初中及以下</option>
                    <option value="高中及中专" <?php  if($basic['education']=='高中及中专') { ?> selected="selected" <?php  } ?>>高中及中专</option>
                    <option value="大专" <?php  if($basic['education']=='大专') { ?> selected="selected" <?php  } ?>>大专</option>
                    <option value="本科" <?php  if($basic['education']=='本科') { ?> selected="selected" <?php  } ?>>本科</option>
                    <option value="硕士及以上" <?php  if($basic['education']=='硕士及以上') { ?> selected="selected" <?php  } ?>>硕士及以上</option>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">职业</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['job'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['job'];?></span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="vocation" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <option value="在校学生" <?php  if($basic['job']=='在校学生') { ?> selected="selected" <?php  } ?>>在校学生</option>
                    <option value="军人" <?php  if($basic['job']=='军人') { ?> selected="selected" <?php  } ?>>军人</option>
                    <option value="私营业主" <?php  if($basic['job']=='私营业主') { ?> selected="selected" <?php  } ?>>私营业主</option>
                    <option value="企业职工" <?php  if($basic['job']=='企业职工') { ?> selected="selected" <?php  } ?>>企业职工</option>
                    <option value="农业劳动者" <?php  if($basic['job']=='农业劳动者') { ?> selected="selected" <?php  } ?>>农业劳动者</option>
                    <option value="政府机关/事业单位工作者" <?php  if($basic['job']=='政府机关/事业单位工作者') { ?> selected="selected" <?php  } ?>>政府机关/事业单位工作者</option>
                    <option value="其他" <?php  if($basic['job']=='其他') { ?> selected="selected" <?php  } ?>>其他</option>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">收入</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['income'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['income'];?></span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="income" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <option value="2000元以下" <?php  if($basic['income']=='2000元以下') { ?> selected="selected" <?php  } ?>>2000元以下</option>
                    <option value="2000-5000" <?php  if($basic['income']=='2000-5000') { ?> selected="selected" <?php  } ?>>2000-5000</option>
                    <option value="5000-10000" <?php  if($basic['income']=='5000-10000') { ?> selected="selected" <?php  } ?>>5000-10000</option>
                    <option value="10000-20000" <?php  if($basic['income']=='10000-20000') { ?> selected="selected" <?php  } ?>>10000-20000</option>
                    <option value="20000以上" <?php  if($basic['income']=='20000以上') { ?> selected="selected" <?php  } ?>>20000以上</option>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">婚姻状况</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['marriage'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['marriage'];?></span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="marry" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <option value="未婚" <?php  if($basic['marriage']=='未婚') { ?> selected="selected" <?php  } ?>>未婚</option>
                    <option value="离异" <?php  if($basic['marriage']=='离异') { ?> selected="selected" <?php  } ?>>离异</option>
                    <option value="丧偶" <?php  if($basic['marriage']=='丧偶') { ?> selected="selected" <?php  } ?>>丧偶</option>
                </select>
            </div>
        </div>
        </li>
        <li class="user_mession">
        <label class="left">是否有房</label>
        <div class="right">
            <div class="select">
                <span class="value">
                <?php  if(empty($basic['house'])) { ?>
                    <span class="pink">请选择</span></span>
                <?php  } else { ?>
                    <?php  echo $basic['house'];?></span>
                <?php  } ?>
                <i class="bot_trged"></i>
                <select id="haveHouse" class="value_select">
                    <option value="0" style="display:none">请选择</option>
                    <option value="已购房" <?php  if($basic['house']=='已购房') { ?> selected="selected" <?php  } ?>>已购房</option>
                    <option value="与父母同住" <?php  if($basic['house']=='与父母同住') { ?> selected="selected" <?php  } ?>>与父母同住</option>
                    <option value="租房" <?php  if($basic['house']=='租房') { ?> selected="selected" <?php  } ?>>租房</option>
                    <option value="其他" <?php  if($basic['house']=='其他') { ?> selected="selected" <?php  } ?>>其他</option>
                </select>
            </div>
        </div>
        </li>
    </ul>
</div>
</body>
<script src="../addons/jy_ppp/js/zepto.min.js"></script>
<script src="../addons/jy_ppp/js/public.js"></script>
<script src="../addons/jy_ppp/js/waiting.js"></script>
<script src="../addons/jy_ppp/js/area_id.js"></script>
<script>
$(function () {
//点击提交

    $("#province").html(area_array[<?php  echo $member['province'];?>]);
    $("#city").html(sub_array[<?php  echo $member['province'];?>][<?php  echo $member['city'];?>]);
    $("#live_province").empty();
    <?php  if(empty($member['province'])) { ?>
        for(var i in area_array){
            $("#live_province").append('<option value="'+ i+'">'+ area_array[i]+'</option>');
        }
    <?php  } else { ?>
        for(var i in area_array){
            if(<?php  echo $member['province'];?>==i)
            {
                $("#live_province").append('<option value="'+ i+'" selected>'+ area_array[i]+'</option>');
            }
            else
            {
                $("#live_province").append('<option value="'+ i+'">'+ area_array[i]+'</option>');
            }
        }
    <?php  } ?>
    
    $("#live_city").empty();
    <?php  if(empty($member['city'])) { ?>
        for(var i in sub_array[<?php  echo $member['province'];?>]){
            $("#live_city").append('<option value="'+ i+'">'+ sub_array[<?php  echo $member['province'];?>][i]+'</option>');
        }
    <?php  } else { ?>
        for(var i in sub_array[<?php  echo $member['province'];?>]){
            if(<?php  echo $member['city'];?>==i)
            {
                $("#live_city").append('<option value="'+ i+'" selected>'+ sub_array[<?php  echo $member['province'];?>][i]+'</option>');
            }
            else
            {
                $("#live_city").append('<option value="'+ i+'">'+ sub_array[<?php  echo $member['province'];?>][i]+'</option>');
            }
        }
    <?php  } ?>


    $("#search_sure").bind("click",function(){
        var nickname=$("#nickname").val();
        var year=$("#basic_birthday select").eq(0).val();
        var month=$("#basic_birthday select").eq(1).val();
        var day=$("#basic_birthday select").eq(2).val();
        var province=$("#live select").eq(0).val();
        var city=$("#live select").eq(1).val();
        var basic_height=$("#basic_height").val();
        var basic_weight=$("#basic_weight").val();
        var basic_blood=$("#bloodType").val();
        var basic_edu=$("#education").val();
        var basic_work=$("#vocation").val();
        var basic_income=$("#income").val();
        var basic_marry=$("#marry").val();
        var basic_have=$("#haveHouse").val();
        // ajax-----

        var param={nicheng: nickname, year:year, month:month, day:day, province:province, city:city, height:basic_height, weight:basic_weight, blood:basic_blood, education:basic_edu, job:basic_work, income:basic_income, marriage:basic_marry, house:basic_have };
        <?php  if(empty($_GPC['xuniid']) || $member['type']!=3) { ?>
            $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('basic',array('op'=>'add')),2);?>", data: param, dataType: 'text', type: 'post', success: function (text) {
                if(text==1){
                    $.tips("保存成功!");
                    location.href = "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('geren'),2)?>";
                }else{
                    $.tips("网络不畅，请稍候再试")
                }
            }, error: function () {
                $.tips("网络不畅，请稍候再试");
                }
            })
        <?php  } else { ?>
            $.ajax({url: "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('basic',array('op'=>'add','xuniid'=>$_GPC['xuniid'])),2);?>", data: param, dataType: 'text', type: 'post', success: function (text) {
                if(text==1){
                    $.tips("保存成功!");
                    location.href = "<?php  echo $_W['siteroot'].'app/'.substr($this->createMobileUrl('geren',array('xuniid'=>$_GPC['xuniid'])),2)?>";
                }else{
                    $.tips("网络不畅，请稍候再试")
                }
            }, error: function () {
                $.tips("网络不畅，请稍候再试");
                }
            })
        <?php  } ?>
    });

    $(".select select").bind("change",function(){
        var self = this;
        if ($(self).val() != 0) {
            var str = $(self).find("option:selected").text();
            $(self).siblings("span").text(str);
        }
    });

    $("#live_province").bind("change",function(){
        var p=$("#live select").eq(0).val();
        $("#live_city").empty();
        var temp=1;
        for(var i in sub_array[p]){
            if(temp==1)
            {
                $("#city").html(sub_array[p][i]);
                temp++;
            }
            $("#live_city").append('<option value="'+ i+'">'+ sub_array[p][i]+'</option>');
        }
    });
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('inc/footer', TEMPLATE_INCLUDEPATH)) : (include template('inc/footer', TEMPLATE_INCLUDEPATH));?>
</html>