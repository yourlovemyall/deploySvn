<?php 
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    
?>
<div class="container-fluid">
    <div class="row">
        <?php echo $this->render("_sidebar",array("active"=>$active ))?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <p>
                <div class="btn-group">
                    <span id="btn_new_vn"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-user">添加用户</button></span>
                </div>
            </p>
            <div class="modal fade" id="add-new-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">添加新用户</h4>
                        </div>
                        <div class="modal-body">
                            <?php $form=ActiveForm::begin(['id'=>'server-form','enableAjaxValidation'=>true,'action'=>false]); ?>
                                <br/>
                                <?=$form->field($model,'user')->textInput(["placeholder"=>"user"])->label('用户名'); ?>
                                <?=$form->field($model,'email')->textInput(['placeholder'=>'email'])->label("邮箱"); ?>
                                <label class="control-label" for="group">角色</label>
                                <select  class="form-control" name="YiiUser[gid]"/>
                                    <?php foreach($groups as $val){ ?>
                                    <option value="<?=$val['g_id']?>"><?=$val['g_name']?></option>
                                    <?php }?>
                                  </select>
                                <?php ActiveForm::end()?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="add_new_user();">保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <table class="table table-striped table-bordered table-responsive dataTable no-footer"  id="table-ajax" role="grid"  style="width: 100%;">
                    <thead>
                        <tr role="row">
                            <th>序号</th>
                            <th>用户名</th>
                            <th>邮箱</th>
                            <th>角色</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($models as $k => $val) {
                            $cls = $k % 2 ? "odd" : "even";
                            ?>
                            <tr class="<?php echo $cls ?>">
                                <td><?php echo $val['id'] ?></td>
                                <td><?php echo $val['user'] ?></td>
                                <td><?php echo $val['email'] ?></td>
                                <td><?php echo app\models\YiiGroup::findOne($val['gid'])->g_name ?></td>
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
    function add_new_user () {
        $.ajax({
            url : "<?php echo Yii::$app->urlManager->createUrl("admin/help/adduser")?>",
            data : $("#server-form").serialize(),
            type : "post",
            dataType: "json",
            success : function (msg){
                if(msg.rs==='success') {
                    alert(msg.info);
                    window.location.reload();
                }else{
                    alert(msg.info);
                    $("#add-new-user").modal('hide');
                }
            }
        });
    };
</script>

