<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'บริหารจดหมายข่าว';
?>
<div class="slider-index">
    <div class="page-header"><?= Html::encode($this->title) ?></div>
    <div class="row">
        <div class="col-lg-4">
            <a href="<?= Url::to(['newsletter/update']) ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> เพิ่มจดหมายข่าว</a>
        </div>
        <div class="col-lg-8">
            
        </div>
    </div><br/>
    <?php
    echo GridView::widget([
        'dataProvider' => $model->search(),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '76%'],
                'header' => 'ชื่อเรื่อง',
                'format' => 'raw',
                'value' => function($model, $xxx){
                    return 'ปีที่: '.$model->years.' ฉบับที่: '.$model->volumn;
                }
            ],
            [
                'headerOptions' => ['width' => '5%', 'style' => 'text-align:center;'],
                'contentOptions' => [ 'align' => 'center'],
                'class' => 'yii\grid\CMSColumn',
                'template' => '{published}',
                'header' => 'แสดง'
            ],
            [
                'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                'contentOptions' => [ 'align' => 'center'],
                'class' => 'yii\grid\CMSColumn',
                'template' => '{up} {down}',
                'header' => 'เรียง'
            ],
            [
                'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                'contentOptions' => [ 'align' => 'center'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'header' => 'แก้ไข'
            ],
        ]
    ]);
    ?> 
</div>
