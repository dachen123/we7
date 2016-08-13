<?php
global $_W,$_GPC;
	    $weid=$_W['uniacid'];
	    header("Content-Type:text/html;charset=utf-8"); 

        $tmp = $_FILES['file']['tmp_name']; 
        if (empty ($tmp)) { 
            echo '请选择要导入的Excel文件！'; 
            
            exit; 
        } 
        
        $file_types = explode ( ".", $_FILES ['file'] ['name'] );
        $file_type = $file_types [count ( $file_types ) - 1];
        
        if (strtolower ( $file_type ) != "xls")              
        {
            message( '不是Excel文件，重新上传' );
        }
        
        require_once '../framework/library/phpexcel/PHPExcel.php';
        
        $PHPExcel = new PHPExcel(); 
        $Obj = new PHPExcel_Reader_Excel2007();
        
        //为了可以读取所有版本Excel文件
        if(!$Obj->canRead($tmp))
        {						
        	$Obj = new PHPExcel_Reader_Excel5();	
        	if(!$Obj->canRead($tmp))
        	{						
        		echo '未发现Excel文件！';
        		return;
        	}
        }
        
        $Obj->setReadDataOnly(true);
        $phpExcel = $Obj->load($tmp);
        
        
        //获取当前活动sheet
        $objWorksheet = $phpExcel->getActiveSheet();
        //获取行数
        $highestRow = $objWorksheet->getHighestRow();
        //获取列数
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        //循环输出数据 
        $data = array();
        for($row = 1; $row <= $highestRow; ++$row) {
        	$name=$objWorksheet->getCellByColumnAndRow(A, $row)->getValue();
        
        	$nickname=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_nickname')." WHERE weid=$weid AND name='".$name."' ");
        	if(empty($nickname))
        	{
        	    $data=array(
        	            'weid'=>$weid,
        	            'name'=>$name,
        	            'displayorder'=>0,
        	            'description'=>'',
        	            'enabled'=>1,
        	        );
        	    pdo_insert("jy_ppp_nickname",$data);
        	}


        }
        //echo '<pre>';
        message("导入数据成功！",$this->createWebUrl('nickname'),'success');