<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'บริหารภาพกิจกรรม';
?>
<div class="row">
    <div class="page-header"><?= Html::encode($this->title) ?></div>
</div>
<br/>
<div class="gallery-index">

    <div class="row">
        <div class="col-lg-4">
            <a href="<?= Url::to(['/admin/gallery/update']) ?>" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> สร้างอัลบั้ม</a>
        </div>
        <div class="col-lg-8">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'article-form',
                        'options' => [ 'class' => 'form-inline pull-right'],
                        'fieldConfig' => [
                            'template' => "{label}{input}",
                            'labelOptions' => [ 'class' => 'sr-only'],
                        ],
            ]);
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'search')->input('text', [ 'style' => 'width: 230px']); ?>
                    <?= $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), [ 'style' => 'width: 120px;', 'onchange' => 'form.submit();']); ?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', [ 'class' => 'btn btn-danger']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div><br/>
    <?php
    echo GridView::widget([
        'dataProvider' => $model->search($model->langs),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '85%'],
                'format' => 'raw',
                'value' => function($model) {
            $mPath = \Yii::getAlias('@webroot') . '/images/gallery/cat_' . $model->id;
            $mUrl = \Yii::getAlias('@web') . '/images/gallery/cat_' . $model->id;
            if (!is_dir($mPath)) {
                mkdir($mPath);
                chmod($mPath, '777');
            }
            foreach (scandir($mPath) as $img) {
                if ($img != '.' && $img != '..' && $img != 'thumb') {
                    $mThumb = $mUrl . '/thumb/' . $img;
                }
            }
            $html = '<a href="' . Url::to(['gallery/view', 'id' => $model->id]) . '">';
            $html .= '<img src="' . $mThumb . '" width="120px" style="float:left;margin-right:5px;"/>';
            $html .= $model->title . '</a>';
            return $html;
        },
            ],
            [
                'headerOptions' => ['width' => '10%', 'style' => 'text-align:center;'],
                'contentOptions' => ['align' => 'center'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} &nbsp;{delete}',
                'header' => 'แก้ไข',
            ],
        ]
    ]);
    ?>
</div>

