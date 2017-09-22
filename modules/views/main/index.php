<?php 
    use app\components\menu\menu;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <?php foreach (menu::busyNav(Yii::$app->user->identity->gid) as $val){ ?>
                <li <?php if($val['id'] == $id) echo 'class="active"' ?>><a href="<?= Yii::$app->urlManager->createUrl(array('admin/main/index', "id" => $val['id'])) ?>"><?=$val['sysname']?></a></li>
                <?php }?>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <ul class="nav nav-tabs">
                <?php foreach ($servers as $k=>$val){?>
                <li <?php if($k==0) echo "class=\"active\""?>>
                        <a href="#bu_info_<?php echo $val['si_id']?>" role="tab" data-toggle="tab"><?php echo $val['si_backup']?></a>
                    </li>
                <?php }?>
            </ul>
            <div class="tab-content">
                <?php foreach ($servers as $k=>$val){?>
                    <div class="tab-pane <?php if($k==0) echo "active"?>" id="bu_info_<?php echo $val['si_id']?>">
                        <?php echo $this->render("_pane",array("id"=>$id,"siid"=>$val['si_id']))?>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>