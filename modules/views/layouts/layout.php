<?php 
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use app\components\menu\menu
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="../../favicon.ico">-->
    <!-- Bootstrap core CSS -->
    <link href="/static/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/css/dashboard.css" rel="stylesheet">
    <link href="/static/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/static/css/datepicker3.css" rel="stylesheet">
    <link href="/static/css/zTreeStyle/zTreeStyle.css" rel="stylesheet">

    <script src="/static/js/jquery-1.11.1.min.js"></script>
    <script src="/static/js/bootstrap.min.js"></script>
    <script src="/static/js/jquery.dataTables.min.js"></script>
    <script src="/static/js/dataTables.bootstrap.js"></script>
    <script src="/static/js/bootstrap-datepicker.js"></script>
    <script src="/static/js/locales/bootstrap-datepicker.zh-CN.js"></script>
    <script src="/static/js/highcharts.js"></script>
    <script src="/static/js/common.js"></script>
    <script src="/static/js/template.js"></script>

    <title><?php echo Html::encode(Yii::$app->name); ?></title>
</head>

<body>
<?php if(isset($this->context->active)){ ?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">金斧子OSS系统</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                
                <?php foreach(menu::Nav(Yii::$app->user->identity->gid) as $key=>$val) {?>
                <li <?php if($this->context->active==$val['mid']) echo 'class="active"'?> > <a href="<?php echo isset($this->context->mid) && $this->context->mid!=FALSE ? Yii::$app->urlManager->createUrl(array("{$val['m_code']}","id"=>$this->context->mid)) : Yii::$app->urlManager->createUrl("{$val['m_code']}")?>"><?php echo $val['m_name']?></a></li>
                <?php }?>
            </ul>
            <div class="navbar-form navbar-right">
                <span class="dl-log-user" style="color:#fff;" id="<?=Yii::$app->user->getId()?>">  欢迎您，<?=  app\models\YiiGroup::findOne(Yii::$app->user->identity->gid)->g_name?>(<?=Yii::$app->user->identity->user?>)</span>
                <a href='#' data-toggle="modal" data-target="#modify-dialog" >修改密码</a>&nbsp;&nbsp;
                <a href="<?=Yii::$app->urlManager->createUrl(['admin/index/logout'])?>" title="退出系统" class="dl-log-quit">[退出]</a>&nbsp;&nbsp;
            </div>
        </div>
         
    </div>
</div>
<div class="modal fade" id="modify-dialog" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">修改密码</h4>
            </div>
            <div class="modal-body">
                <?php $form=ActiveForm::begin(['id'=>'deploy-form','enableAjaxValidation'=>true,'action'=>FALSE]); ?>
                    <label for="sysname">新密码:</label>
                    <input type="password" class="form-control" id="pwd-<?=Yii::$app->user->getId()?>" name="pwd" >
                <?php ActiveForm::end()?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_pwd();">保存</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<?php echo $content; ?>
<div class="clear"></div>

<!--<script src="/static/js/web/bu_info_init.js"></script>
<script src="/static/js/web/server_info_init.js"></script>
<script src="/static/js/web/url_info_init.js"></script>-->
<script>
    save_pwd = function () {
        var new_pwd = $("#pwd-<?=Yii::$app->user->getId()?>").val();
        if (!new_pwd)   alert("新密码不能为空");
        $.ajax({
            url:"<?php echo Yii::$app->urlManager->createUrl("admin/index/modifypwd")?>",
            data:{pwd:new_pwd},
            type:"post",
            dataType: "json",
            success:function (msg){
                if(msg.rs=="success"){
                    alert(msg.info);
                    $("#modify-dialog").modal('hide');
                }else{
                    alert(msg.info);
                }
            }
        })
    }
</script>
</html>
