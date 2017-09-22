<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
?>
<?php if(Yii::$app->session->hasFlash('success_sys')):?>
    <div class="alert alert-success text">
        <b><?=Yii::$app->session->getFlash('success_sys')?></b>
    </div>
<?php endif?>

<?php if(Yii::$app->session->hasFlash('error_sys')):?>
    <div class="alert alert-error text">
        <b><?=Yii::$app->session->getFlash('error_sys')?></b>
    </div>
<?php endif?>

<?php $form=ActiveForm::begin(['id'=>'deploy-form','enableAjaxValidation'=>true,'action'=>Yii::$app->urlManager->createUrl("admin/help/addsystem")]); ?>
<br/>
<div class="form-group">
    <label for="sysname">系统名称:</label>
    <input type="text" class="form-control" id="sysname" name="YiiSystem[sysname]" >
</div>
<div class="form-group">
    <label for="sysname">英文简称:</label>
    <input type="text" class="form-control" id="ename" name="YiiSystem[ename]" >
</div>
<div class="form-group">
    <label for="ins_name">安装目录名称：</label>
    <input type="text" class="form-control" id="ename" name="YiiSystem[ins_name]" >
</div>
<?=Html::submitButton('保存',['class'=>'btn btn-primary'])?>
<!--<button type="button" class="btn btn-default" onclick="javascript:addsystem()">添加</button>-->
<?php ActiveForm::end()?>