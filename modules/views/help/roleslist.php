<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title"><font color='red'><?= $datas['gname'] ?></font>角色授权</h4>
        </div>
        <div class="modal-body">
            <ul class="list-group">
                <?php foreach ($menus as $k => $v) { ?>
                    <li class="list-group-item">
                        <label>
                            <input type="checkbox" class="checkbox-menus" <?php if(\app\models\YiiGmenus::find()->where(array("code"=>"menus","g_id"=>$datas['gid'],"m_id"=>$v['mid']))->one()) echo "checked"?> value="<?= $v['mid'] ?>">
                            <?= $v['m_name'] ?>
                        </label>
                        <?php if (!empty($v['children'])) { ?>
                            <ul class="list-child">
                                <?php foreach ($v['children'] as $vs) { ?>
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox-menus" <?php if(\app\models\YiiGmenus::find()->where(array("code"=>"menus","g_id"=>$datas['gid'],"m_id"=>$vs['mid']))->one()) echo "checked"?>  value="<?= $vs['mid'] ?>">
                                            <?= $vs['m_name'] ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                        <?php if ($k == 2) { ?>
                            <ul class="list-child">
                                <?php foreach ($sys as $vb) { ?>
                                    <li class="list-group-item">
                                        <label>
                                            <input type="checkbox" class="checkbox-sys" <?php if(\app\models\YiiGmenus::find()->where(array("code"=>"sys","g_id"=>$datas['gid'],"m_id"=>$vb['id']))->one()) echo "checked"?> value="<?= $vb['id'] ?>">
                                            <?= $vb['sysname'] ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="save_permission();">保存</button>
        </div>
    </div>
</div>
<script>
    save_permission = function () {
        var mid_arr = new Array();
        var sid_arr = new Array();
        $(".checkbox-menus").each(function (){
           if(this.checked)  mid_arr.push($(this).val()); 
        });
        
        $(".checkbox-sys").each(function (){
           if(this.checked)  sid_arr.push($(this).val()); 
        });
        $.ajax({
            url:"<?php echo Yii::$app->urlManager->createUrl("admin/help/permission")?>",
            data:{mids:mid_arr,sids:sid_arr,gid:<?=$datas['gid'] ?>},
            type:"post",
            dataType: "json",
            success:function (msg){
                if(msg.rs=="success"){
                    alert(msg.info);
                    $("#roles_perms").modal('hide');
                }else{
                    alert(msg.info);
                }
            }
        })
    }
</script>