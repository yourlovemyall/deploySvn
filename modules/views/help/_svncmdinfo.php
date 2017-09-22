<?php 
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">修改操作</h4>
        </div>
        <div class="modal-body">
            <?php $form = ActiveForm::begin(['id' => 'server-form-edit', 'enableAjaxValidation' => true, 'action' => false]); ?>
            <?= $form->field($model, 'id')->Input("hidden",["value"=>"{$res->id}"])->label(''); ?>
            <?= $form->field($model, 'ps_note')->textInput(["placeholder" => "如 开发机发版","value"=>"{$res->ps_note}"])->label('说明'); ?>
            <?= $form->field($model, 'ps_cmd')->textInput(['placeholder' => 'salt command',"value"=>"{$res->ps_cmd}"])->label("操作命令"); ?>
            <label class="control-label" for="group">关联系统</label>
            <div class="rowa">
                <div class="btn-group" data-toggle="buttons">
                    <?php foreach ($systems as  $val) { ?>
                    <?php $c=array(); $res->sys_id == $val['id'] ? $c =array("active","checked") : array()?>
                        <label class="btn btn-default <?php echo $c[0]?>">
                            <input type="radio"  name="YiiPublishSvn[sys_id]" <?php echo $c[1]?> value="<?= $val['id'] ?>" /> <?= $val['sysname'] ?>
                        </label> 
                    <?php } ?>

                </div>
            </div>

            <?php ActiveForm::end() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="add_publishsvn('server-form-edit');">保存</button>
        </div>
    </div>
</div>
