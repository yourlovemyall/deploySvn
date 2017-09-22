<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
?>

<div class="alert alert-success text" style="display: none;">
        <b>save success！</b>
    </div>



    <div class="alert alert-error text" style="display: none;">
        <b>save fail！</b>
    </div>


<?php $form=ActiveForm::begin(['id'=>'server-form','enableAjaxValidation'=>true ,'action'=>FALSE]); ?>
<br/>

<div class="form-group">
    <label for="ser_local_ip">请选择项目名称</label>
    <div class="rowa">
        <div class="btn-group" data-toggle="buttons">
            <?php foreach ($datas as $k=> $val) { ?>
                <label class="btn btn-default" onclick="sys_item(<?= $val['id']?>)">
                    <input type="radio" name="YiiServer[sys_id]"  value="<?= $val['id'] ?>" /> <?= $val['sysname'] ?>
                </label> 
            <?php } ?>
        </div>
    </div>
    
</div>


<div class="form-group">
    <label for="ser_name">服务器简称</label>
    <div class="rowa">
        <div class="btn-group" data-toggle="buttons" id="servers-group">

        </div>
    </div>
</div>

<div class="form-group">
    <label for="ser_usefor">服务器用途</label>
    <input type="text" class="form-control" id="ename" name="YiiServer[ser_usefor]" />
</div>

<?=Html::Button('保存',['class'=>'btn btn-primary','id'=>"Yii-server-sub"])?>

<!--<button type="button" class="btn btn-default" onclick="javascript:addsystem()">添加</button>-->
<?php ActiveForm::end()?>
<script>
    function sys_item (id) {
        $.ajax({
            url:"<?php echo Yii::$app->urlManager->createUrl("admin/help/serverlist")?>",
            data: {id:id},
            type:"post",
            dataType:"html",
            success : function (msg){
                $("#servers-group").html(msg);
            }
        });
    };
    
    $("#Yii-server-sub").click(function(){
        $.ajax({
            url:"<?php echo Yii::$app->urlManager->createUrl("admin/help/addserver")?>",
            data: $("#server-form").serialize(),
            type:"post",
            dataType:"json",
            success : function (msg){
                if(msg.rs==='success'){
                    sys_item(msg.info);
                    $(".alert-success").show(0).delay(3000).hide(0); 
                }
                else
                    $(".alert-error").show(10).delay(3000).hide(10);
            }
        });
    });
    
    
</script>