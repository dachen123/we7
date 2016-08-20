<?php
    $sql = 'select distinct child_cp_id from '.tablename('channel_pay');
    $child_cp_ids = pdo_query($sql);
    $sql = 'select distinct package_id from '.tablename('channel_pay');
    $package_ids = pdo_query($sql);

?>
<html>
    <head>
    </head>
    <body>
       <select id="child_cp_list">
            <?php
                foreach ($child_cp_ids as $child_cp)
                    echo '<option>'.$child_cp.'</option>';
            ?>
       </select> 
       <select id="package_list">
            <?php
                foreach ($package_ids as $package_id)
                    echo '<option>'.$package_id.'</option>';
            ?>
       </select> 
       <button> 查询</button>
    </body> 
</html>
