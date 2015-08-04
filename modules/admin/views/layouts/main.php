<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$this->registerJs('$().scroll(function(){$(".app-title").css("top",Math.max(0,250-$(this).scrollTop()));});');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link href="<?= Yii::getAlias('@web') ?>/css/cms.css" rel="stylesheet">
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => '<i class="glyphicon glyphicon-home"></i> มหาวิทยาลัยขอนแก่น วิทยาเขตหนองคาย',
            'brandUrl' => Yii::$app->homeUrl,
            'innerContainerOptions' => [
                'class' => 'container-fluid'
            ],
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
                'style' => 'padding-right: 15px;'
            ],
        ]);

        $menuItems1[] = ['label' => '<i class="glyphicon glyphicon-off"></i>  ออกจากระบบ', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $menuItems1,
        ]);

        NavBar::end();
        ?>

        <div class="container-fluid">
            <div class="row app-title">
                <span><?php echo Yii::$app->name; ?></span>
                <div class="pull-right">
                    สำหรับเจ้าหน้าที่
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3 col-md-2" style="background-color: #FFFFFF;">
                    <?php require 'menus.php'; ?>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10">
                    <?= $content ?>
                </div>
            </div>
        </div>
        <br/>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
