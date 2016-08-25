<?php
    $uniacid = 3;
    require './framework/bootstrap.inc.php';
    $qr_sql = 'select * from '.tablename('qrcode').' where uniacid='.$uniacid;
    $qrcodes = pdo_fetchall($qr_sql);
	//print_r( $qrcodes);
	//die;
    foreach ($qrcodes as $qrcode){
        $ids = explode("_",$qrcode['scene_str']);
        $agent_list[] = $ids[0];
        $child_cp_list[] = $ids[1];
        $package_list[] = $ids[2];
        $uni_agent_list = array_unique($agent_list);
        $uni_child_cp_list = array_unique($child_cp_list);
        $uni_package_list = array_unique($package_list);
    }

    if ($_GET['child_cp_id']){
	echo '<script>
		document.getElementById("child_cp_list").value = '.$_GET['child_cp_id'].';	
	</script>';

    	$fs_scene_str = '';
    	if ($_GET['agent_id'] == '全部'){
    	    $fs_agent = ''; 
    	    $fs_scene_str = '%';
    	}else{
    	
    	    $fs_agent = ' and agent_id='.$_GET['agent_id']; 
    	}
        if ($_GET['child_cp_id'] == '全部'){
            $fs_child_cp = ''; 
	    if( empty($fs_scene_str)){
            	if ($_GET['agent_id'] != '全部'){
            	    $fs_scene_str = $_GET['agent_id'].'_%';
            	}else{
            	    $fs_scene_str = '%';
	    	}
	    }
        }else{
        
            $fs_child_cp = ' and child_cp_id='.$_GET['child_cp_id']; 
    }
    if ($_GET['package_id'] == '全部'){
        $fs_package = ''; 
	if( empty($fs_scene_str)){
        	if ($_GET['child_cp_id'] != '全部' && $_GET['agent_id'] != '全部'){
        	    $fs_scene_str = $_GET['agent_id'].'_'.$_GET['child_cp_id'].'_%';
        	}else{
        		$fs_scene_str = '%';
		}
	}
    }else{
    
        $fs_package = ' and package_id='.$_GET['package_id']; 
    }
    if($_GET['package_id'] != '全部' && $_GET['agent_id'] != '全部' && $_GET['child_cp_id'] != '全部'){
        $fs_scene_str = $_GET['agent_id'].'_'.$_GET['child_cp_id'].'_'.$_GET['package_id'];
    }

        $sql_temp = 'select sum(fee) as `sum` from '.tablename('channel_pay').'where uniacid='.$uniacid.' and (createtime > :begin_time and createtime <= :end_time) '.$fs_child_cp.$fs_agent.$fs_package;
        //获取当天的年份
        //$y = date("Y");
        ////获取当天的月份
        //$m = date("m");
        ////获取当天的号数
        //$d = date("d");
        ////将今天开始的年月日时分秒，转换成unix时间戳(开始示例：2015-10-12 00:00:00)
        //$todayTime= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳
        //$now_time = time();
        $begin_time = strtotime($_GET['begin_date'].' 00:00:00');
        $end_time = strtotime($_GET['end_date'].' 23:59:59');
        

        $today_sum = pdo_fetch($sql_temp,array(':begin_time' => $begin_time,':end_time' => $end_time));
        $sql_temp2 = 'select sum(fee) as `sum` from '.tablename('channel_pay').'where uniacid='.$uniacid.$fs_child_cp.$fs_agent.$fs_package;
        $total_sum = pdo_fetch($sql_temp2);

        $sql_subscribe_by_date = 'select count(*) as `count` from '.tablename('qrcode_stat').' where uniacid='.$uniacid.' and `scene_str` like "'.$fs_scene_str.'" and (createtime > :begin_time and createtime <= :end_time) and `type`=1';
	//echo $sql_subscribe_by_date;
	//die;
	$count_by_date = pdo_fetch($sql_subscribe_by_date,array(':begin_time' => $begin_time,':end_time' => $end_time));
	$sql_subscribe_all = 'select count(*) as `count` from '.tablename('qrcode_stat').'where uniacid='.$uniacid.' and `scene_str` like "'.$fs_scene_str.'" and `type`=1';
	$count_all = pdo_fetch($sql_subscribe_all);
        $sql_unsubscrib_by_date = 'select count(*) as `count` from (select * from ims_qrcode_stat where uniacid='.$uniacid.' and type=1) as a join (select * from ims_stat_msg_history where uniacid='.$uniacid.' and type="unsubscrib") as b on a.openid=b.from_user where b.createtime > :begin_time and b.createtime <= :end_time';
        $sql_unsubscrib_all = 'select count(*) as `count` from (select * from ims_qrcode_stat where uniacid='.$uniacid.' and type=1) as a join (select * from ims_stat_msg_history where uniacid='.$uniacid.' and type="unsubscrib") as b on a.openid=b.from_user';
        $unsubscrib_count_by_date = pdo_fetch($sql_unsubscrib_by_date,array(':begin_time' => $begin_time,':end_time' => $end_time));
        $unsubscrib_count_all = pdo_fetch($sql_unsubscrib_all);
        //print_r( $unsubscrib_count_by_date);
        //print_r( $unsubscrib_count_all);
	//echo $unsubscrib_count_by_date['count'];
        //die;
	$fee_sum = intval($today_sum['sum']);
	//echo intval($fee_sum * 0.7)/50;
	//die;
	if ($fee_sum > 200){
		$fee_sum = intval(intval($fee_sum * 0.75 / 50) * 50);
	}
	$subcrib_count = intval($count_by_date['count']);
	if ($subcrib_count > 30){
		$subcrib_count = intval($subcrib_count * 0.7);	
	}
	$unsubcrib_count = intval($unsubscrib_count_by_date['count']);
	if ($unsubcrib_count > 30){
		$unsubcrib_count = intval($unsubcrib_count * 0.7);
	}
    }

?>
<html>
    <head>
    <script>
         function searchFun(){
             var agent_id = document.getElementById('agent_list').value;
             var child_cp_id = document.getElementById('child_cp_list').value;
             var package_id = document.getElementById('package_list').value;
             var begin_date = document.getElementById('begin_date').value;
             var end_date = document.getElementById('end_date').value;
             window.location.href = window.location.href.split('?')[0] +'?child_cp_id='+child_cp_id+'&package_id='+package_id+'&agent_id='+agent_id+'&begin_date='+begin_date+'&end_date='+end_date;
         
         }
    </script>
    </head>
    <body align="center">
       <label>代理商ID：</label>
       <select id="agent_list">
            <?php
                foreach ($uni_agent_list as $agent)
                    echo '<option>'.$agent.'</option>';
                echo '<option selected="selected">全部</option>';
            ?>
       </select>
       <label>子渠道ID：</label>
       <select id="child_cp_list">
            <?php
                foreach ($uni_child_cp_list as $child_cp)
                    echo '<option>'.$child_cp.'</option>';
                echo '<option selected="selected">全部</option>';
            ?>
       </select>
        <label>包ID：</label>
       <select id="package_list">
            <?php
                foreach ($uni_package_list as $package)
                    echo '<option>'.$package.'</option>';
                echo '<option selected="selected">全部</option>';
            ?>
       </select> 
        <label>开始日期:</label>
        <input type="date" id="begin_date" value="<?php echo date('Y-m-d',time())?>"></input>
        <label>结束日期:</label>
        <input type="date" id="end_date" value="<?php echo date('Y-m-d',time())?>"></input>
       <button id='search' onclick="searchFun()"> 查询</button>
	<div align="center">
	<table border="1">
	  <tr>
	    <th>时间</th>
	    <th>累计充值</th>
	    <!--<th>充值总和</th>-->
	    <th>累计关注</th>
	    <!--<th>关注总量</th>-->
	    <th>累计取消关注</th>
	    <!--<th>取消关注总量</th>-->

	  </tr>
	  <tr>
            <?php 
                if(!empty($_GET['package_id'])){
	                echo '<td>'.$_GET['begin_date'].' ~ '.$_GET['end_date'].'</td>';
	                echo '<td>'.$fee_sum.'</td>';
	                //echo '<td>'.$total_sum['sum'].'</td>';
	                echo '<td>'.$subcrib_count.'</td>';
	                //echo '<td>'.$count_all['count'].'</td>';
	                echo '<td>'.$unsubcrib_count.'</td>';
	                //echo '<td>'.$unsubscrib_count_all['count'].'</td>';

                }
            ?>
	  </tr>
	</table>
	</div>

    </body> 
</html>


