<?php 
    use app\components\menu\menu;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <?php foreach (menu::busyNav(Yii::$app->user->identity->gid) as $val){ ?>
                <li <?php if($val['id'] == $id) echo 'class="active"' ?>><a href="<?= Yii::$app->urlManager->createUrl(array('admin/boper/index', "id" => $val['id'])) ?>"><?=$val['sysname']?></a></li>
                <?php }?>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#boper" role="tab" data-toggle="tab">业务部署</a>
                </li>
                <li>
                    <a href="#bvsn" role="tab" data-toggle="tab">版本管理</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="boper">
                    <?php echo $this->render("boper",array("id"=>$id))?>
                </div>
                <div class="tab-pane" id="bvsn">
                    <?php echo $this->render("bvsn",array("id"=>"$id"))?>
                </div>
            </div>
            <?php echo $this->render("_vselect",array("id"=>$id))?>
        </div>
    </div>
</div>
