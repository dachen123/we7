<?php
    require './framework/bootstrap.inc.php';
    $sql = 'select distinct `child_cp_id` from '.tablename('channel_pay').'where `uniacid`=3';
    $child_cp_ids = pdo_fetchall($sql);
    //die;

    $sql = 'select distinct package_id from '.tablename('channel_pay').'where `uniacid`=3';
    $package_ids = pdo_fetchall($sql);

    $sql_temp = 'select sum(fee) as `sum` from '.tablename('channel_pay').'where uniacid=3 and (createtime > :begin_time and createtime <= :now_time) and package_id=:package_id';
    //获取当天的年份
    $y = date("Y");
    //获取当天的月份
    $m = date("m");
    //获取当天的号数
    $d = date("d");
    //将今天开始的年月日时分秒，转换成unix时间戳(开始示例：2015-10-12 00:00:00)
    $todayTime= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳

    $now_time = time();
    $today_sum = pdo_fetch($sql_temp,array(':begin_time' => $todayTime,':now_time' => $now_time,'package_id' => $_GET['package_id']));

?>
<html>
    <head>
    <script>
         function searchFun(){
             var child_cp_id = document.getElementById('child_cp_list').value;
             var package_id = document.getElementById('package_list').value;
             window.location.href = window.location.href.split('?')[0] +'?child_cp_id='+child_cp_id+'&package_id='+package_id;
         
         }
    </script>
    </head>
    <body>
       <!-- <label>子渠道id：</label>
       <select id="child_cp_list">
            <?php
           //     foreach ($child_cp_ids as $child_cp)
		    //echo $child_cp['child_cp_id'];
            //        echo '<option>'.$child_cp['child_cp_id'].'</option>';
            ?>
       </select>-->
        <label>子渠道包id：</label>
       <select id="package_list">
            <?php
                foreach ($package_ids as $package_id)
                    echo '<option>'.$package_id['package_id'].'</option>';
            ?>
       </select> 
       <button id='search' onclick="searchFun()"> 查询</button>
	<br/>
	<table border="1">
	  <tr>
	    <th>packageID</th>
	    <th>当前时间</th>
	    <th>金额</th>
	  </tr>
	  <tr>
            <?php 
                if(!empty($_GET['package_id'])){
	                echo '<td>'.$_GET['package_id'].'</td>';
	                echo '<td>'.date('Y-m-d H:i:s',time()).'</td>';
	                echo '<td>'.$today_sum['sum'].'</td>';
                
                }
            ?>
	  </tr>
	</table>

    </body> 
</html>


