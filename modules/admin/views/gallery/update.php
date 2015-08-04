<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2elRTE\yii2elRTE;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'อัลบั้มภาพ';
?>
<div class="gallery-content">    
    <?php
    $form = ActiveForm::begin([
                'id' => 'gallery-form',
                'options' => ['class' => ''],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="page-header">
        <?= Html::encode($this->title) ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]
        <div class="form-group pull-right">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึกข้อมูล', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'title')->input('text', ['style' => 'width: 400px;']) ?>
            <div class="form-group required" style="padding-left: 0px; padding-right: 0px;">
                <label>คำอธิบายหมวดหมู่</label>
                <?php
                $url1 = Yii::$app->getAssetManager()->publish(Yii::getAlias('@yii2elRTE'));
                echo yii2elRTE::widget(
                        array(
                            'model' => $model,
                            'modelName' => 'tblGallery',
                            'attribute' => 'description',
                            'toolbar' => 'compact',
                            //'width' => 400,
                            'height' => 200,
                            'baseUrl' => $url1[1],
                        )
                );
                ?>
                <div class="help-block"></div>
            </div>    
            <?= $form->field($model, 'id',['options'=>['class'=>'sr-only']])->hiddenInput() ?>
            <?= $form->field($model, 'langs',['options'=>['class'=>'sr-only']])->hiddenInput() ?>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>

