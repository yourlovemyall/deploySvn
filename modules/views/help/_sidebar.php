<?php 
    use app\components\menu\menu ;
    use \yii\helpers\Url;
?>

<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <?php foreach (menu::leftNav(Yii::$app->user->identity->gid) as $val){?>
        <li <?php if (Yii::$app->requestedRoute == $val['m_code']) echo 'class="active"' ?>><a href="<?=Yii::$app->urlManager->createUrl("{$val['m_code']}") ?>"><?=$val['m_name']?></a></li>
        <?php }?>
        
    </ul>
</div>