<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'บริหารภาพสไลด์';
?>
<div class="gallery-index">
    <?php
    $form = ActiveForm::begin([
                'id' => 'gallery-form',
                'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'control-label'],
                ],
    ]);
    ?>
    <div class="row">
    <div class="page-header">
        <?= Html::encode($this->title) ?>
        <div class="form-group pull-right">
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> บันทึก', ['class' => 'btn btn-danger']) ?>
            <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn']) ?>
        </div>
    </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'cid')->dropDownList(app\models\TblSlidertype::makeDropDown(),['style'=>'width:200px; max-width: 400px;']); ?>
            
            <?php
            echo $form->field($model, 'upload_files')->widget(FileInput::classname(), [
                'options' => ['multiple' => false],
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => true,
                    'showCaption' => false,
                    'uploadClass' => 'btn btn-info',
                    'removeClass' => 'btn btn-danger',
                    'elCaptionText' => '#customCaption',
                    'initialPreview' => [
                        Html::img("$model->slider_Url", ['class' => 'file-preview-image', 'alt' => '', 'title' => '']),
                        ],
                ]
            ]);
            echo "ขนาดภาพ Slide: 1,263*520 px<br/>ขนาดภาพ Event: 800*600px<br/><br/>";

            echo $form->field($model, 'link_Url')->input('text', ['style' => 'width: 400px;']);
            //echo $form->field($model, 'target')->input('checkbox', ['style' => 'width: 400px;']);
            echo $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput();
            echo $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput();
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
