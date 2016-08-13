<?php

defined('IN_IA') or exit('Access Denied');
class Jy_pppModule extends WeModule{
    public function settingsDisplay($settings){
        global $_W, $_GPC;
        include $this -> template ('setting');
    }
    public function fieldsFormDisplay($rid = 0){
        global $_W;
        load () -> func ('tpl');
        if (! empty ($rid)){
            $reply = pdo_fetch ("SELECT * FROM " . tablename ("jy_ppp_rule") . " WHERE ruleid = :rid ORDER BY `id` DESC", array (':rid' => $rid));
        }
        include $this -> template ('display');
    }
    public function fieldsFormValidate($rid = 0){
        return '';
    }
    public function fieldsFormSubmit($rid){
        global $_GPC, $_W;
        $data = array ('ruleid' => $rid, 'uniacid' => $_W ['uniacid'], 'desc' => $_GPC ['desc'], 'rulename' => $_GPC ['rulename']);
        if ($_GPC ['id']){
            pdo_update ("jy_ppp_rule", $data, array ('id' => $_GPC ['id']));
        }else{
            pdo_insert ("jy_ppp_rule", $data);
        }
        message('添加成功', 'refresh');
    }
    public function ruleDeleted($rid){
        pdo_delete ("jy_ppp_rule", array ('ruleid' => $rid));
    }
}

?>