<?php /* @var $this Controller */ ?>
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

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<?php if (isset($this->active)){ ?>
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
                <li <?php if($this->active=='index') echo 'class="active"'?>><a href="<?php echo Yii::app()->createUrl("/main/index")?>">组织架构</a></li>
                <li <?php if($this->active=='boper') echo 'class="active"'?>><a href="<?php echo Yii::app()->createUrl("/boper/index")?>">业务操作</a></li>
                <li <?php if($this->active=='monitor') echo 'class="active"'?>><a href="<?php echo Yii::app()->createUrl("/monitor/index")?>">个人中心</a></li>
                <li <?php if($this->active=='help') echo 'class="active"'?>><a href="<?php echo Yii::app()->createUrl("/help/index")?>">帮助</a></li>
            </ul>
            <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form>
        </div>
    </div>
</div>
<?php } ?>


<?php echo $content; ?>
<div class="clear"></div>

<script src="/static/js/web/bu_info_init.js"></script>
<script src="/static/js/web/server_info_init.js"></script>
<script src="/static/js/web/url_info_init.js"></script>
<script>
//    $(document).ready(function()
//    {
//        init_real_table();
//        init_server_table();
//        init_url_table();
//    });
</script>
</html>
