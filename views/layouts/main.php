<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\nav\NavX;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>

        <?php $this->beginBody() ?>
        <div class="navbar-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-xs-3 col-md-4">
                        <a href="<?= Url::to(['/']) ?>"><img src="<?= Yii::getAlias('@web') ?>/images/logo_th-TH.png"/></a>
                    </div>
                    <div class="col-xs-12 col-sm-9 col-md-8">
                        <div class="row">
                            <div class="col-sm-12  hidden-xs">
                                <div class="pull-right">
                                    <a href="<?= Url::to(['site/index', '_lang' => 'th-TH']) ?>" title="ภาษาไทย">
                                        <img src="<?= Yii::getAlias('@web') ?>/images/lang-th.png" width="38px" class="pull-right" style="padding: 2px; "/>
                                    </a>
                                    <a href="<?= Url::to(['site/index', '_lang' => 'en-EN']) ?>" title="English">
                                        <img src="<?= Yii::getAlias('@web') ?>/images/lang-en.png" width="38px" class="pull-right" style="padding: 2px; margin-left: 10px;"/>
                                    </a>
                                    <?php
                                    if(Yii::$app->user->isGuest){
                                    ?>
                                    <a href="<?= Url::to(['site/login']) ?>">Login</a>
                                    <?php }else{ ?>
                                    <a href="<?= Url::to(['site/logout']) ?>" data-method="post">Logout</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 5px; padding-bottom: 5px;">
                            <div class="col-xs-12 pull-right  hidden-xs">
                                <?php
                                $mod = new \app\models\LoginForm;
                                $form = ActiveForm::begin([
                                            'id' => 'categories-form',
                                            'options' => ['class' => 'form-inline pull-right'],
                                            'action' => \yii\helpers\Url::to(['site/search']),
                                            'fieldConfig' => [
                                                'template' => '<div class="form-group has-success has-feedback">{input}<span class="glyphicon glyphicon-search form-control-feedback"></span></div>',
                                                'labelOptions' => ['class' => 'sr-only'],
                                            ],
                                ]);
                                ?> 
                                <?= $form->field($mod, 'search')->input('text', ['class' => 'form-control', 'style' => 'width: 350px;', 'placeholder' => Yii::t('app', 'Search')]) ?>                                
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (Yii::$app->language == 'th-TH') {
                        $type = 1;
                    } else {
                        $type = 7;
                    }
                    $menuItems = app\models\Menus::listMenus(0, 0, $type);
                    echo NavX::widget([
                        'options' => ['class' => 'nav-pills pull-right', 'style' => 'z-index: 1000; margin-right: 0px;'],
                        'encodeLabels' => false,
                        'items' => $menuItems,
                    ]);
                    ?>               
                </div>

            </div>
        </div>
        <br/>
        <div class="wrap">            

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer" style="background-color: #212121; color: #FFFFFF;">
            <div class="container">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
                <p class="pull-right"><?= Yii::powered() . ' ' . Yii::getVersion() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
