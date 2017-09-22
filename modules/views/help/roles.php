<?php 
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    
?>
<?= Html::csrfMetaTags() ?>
<div class="container-fluid">
    <div class="row">
        <?php echo $this->render("_sidebar",array("active"=>$active ))?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <p>
                <div class="btn-group">
                    <span id="btn_new_vn"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-roles">添加角色</button></span>
                    
                    
                </div>
            </p>
            <div class="modal fade" id="add-new-roles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"   aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">添加角色</h4>
                        </div>
                        <div class="modal-body">
                            <?php $form=ActiveForm::begin(['id'=>'server-form','enableAjaxValidation'=>true,'action'=>false]); ?>
                                <br/>
                                <?=$form->field($model,'g_name')->textInput()->label('角色'); ?>
                                <?=$form->field($model,'g_desc')->textInput()->label('角色描述'); ?>
                                <?php ActiveForm::end()?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="add_new_roles();">保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="roles_perms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                
            </div>
            <div class="tab-content">
                <table class="table table-striped table-bordered table-responsive dataTable no-footer"  id="table-ajax" role="grid"  style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th>序号</th>
                            <th>角色</th>
                            <th>角色描述</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($models as $k => $val) {
                            $cls = $k % 2 ? "odd" : "even";
                            ?>
                            <tr class="<?php echo $cls ?>">
                                <td><?php echo $val['g_id'] ?></td>
                                <td><?php echo $val['g_name'] ?></td>
                                <td><?php echo $val['g_desc'] ?></td>
                                <td><span id="btn_new_vn"><button type="button" class="btn btn-primary" onclick="javascript:roles_perm(<?= $val['g_id']?>,'<?=$val['g_name']?>')" >角色授权</button></span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="page-right" style="float: right">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                    'hideOnSinglePage'=>false
                ]);
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function add_new_roles () {
        $.ajax({
            url : "<?php echo Yii::$app->urlManager->createUrl("admin/help/addroles")?>",
            data : $("#server-form").serialize(),
            type : "post",
            dataType: "json",
            success : function (msg){
                if(msg.rs==='success') {
                    alert(msg.info);
                    window.location.reload();
                }else{
                    alert(msg.info);
                    $("#add-new-roles").modal('hide');
                }
            }
        });
    };
    function roles_perm (gid,gname) {
        $.ajax({
            url : "<?php echo Yii::$app->urlManager->createUrl("admin/help/roleslist")?>",
            data : {gid:gid,gname:gname},
            type : "post",
            dataType: "html",
            success : function (msg){
                $("#roles_perms").html(msg);
                $("#roles_perms").modal('show');
            }
        });
    }
</script>

