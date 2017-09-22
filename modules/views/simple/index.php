<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use app\components\menu\menu;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <?php foreach (menu::busyNav(Yii::$app->user->identity->gid) as $val) { ?>
                    <li <?php if ($val['id'] == $id) echo 'class="active"' ?>><a href="<?= Yii::$app->urlManager->createUrl(array('admin/simple/index', "id" => $val['id'])) ?>"><?= $val['sysname'] ?></a></li>
<?php } ?>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<?php $form = ActiveForm::begin(['id' => 'com-form', 'enableAjaxValidation' => true, 'action' => FALSE, "options" => ["style" => "margin:20px;"]]); ?>
            <fieldset enable>
                <div class="form-group">
                    <label for="TextInput">command:</label>
                    <?php foreach($list as $v){?>
                    <br/>
                    <label><input type="radio" name="cmd" class="form-radio"  value='<?php echo $v['id']?>' /> <?php echo $v['ps_note']?></label>
                    <?php };?>
                </div>
                
                
                <button type="button" class="btn btn-primary" onclick="form_putin()">Submit</button>
                <div class="form-group" style="margin-top: 20px;">
                    <label for="enable textarea">return:</label>
                    <textarea class="form-control" id="form-ret" rows="20" style="background: black;color: #ffffff ; font-size: 18px;">
                    </textarea>
                </div>
            </fieldset>
<?php ActiveForm::end() ?>
        </div>
    </div>
</div>
<script>
    form_putin = function () {
        var cmd = $('input[type="radio"][name="cmd"]:checked').val();
        if (!cmd ) return;
        $.ajax({
            url: "<?php echo Yii::$app->urlManager->createUrl("admin/simple/push") ?>",
            data: {cmd: cmd},
            type: "post",
            dataType: "json",
            success: function (msg) {
                if (msg.rs == "success") {
                    $("#form-ret").html('');
                    $("#form-ret").html(msg.info);
                } else {
                    alert(msg.info);
                }
            }
        });
    }
</script>
