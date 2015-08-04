<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'เข้าสู่ระบบ';
?>
<div class="site-login">
    <br/>
    <div class="col-sm-7">
        <!--<img src="<?php echo Yii::getAlias('@web'); ?>/images/log-1.jpg" width="100%" />-->
    </div>
    <div class="col-sm-5">
        <h1><?= Html::encode($this->title) ?></h1>

        <!--<p>ลงชื่อเข้าใช้งานด้วย <br/>บัญชีผู้ใช้ของมหาวิทยาลัยขอนแก่น</p>-->

        <?php
        $form = ActiveForm::begin([
                    'id' => 'login-form',
        ]);
        ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <?= Html::submitButton('เข้าสู่ระบบ', ['class' => 'btn btn-primary', 'name' => 'login-button', 'style' => 'width: 150px;']) ?>

        <?php ActiveForm::end(); ?>
        <br/><br/><br/><br/>
    </div>

</div>