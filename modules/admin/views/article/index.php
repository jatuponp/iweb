<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\Categories;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'บริหารบทความ';
?>
<div class="site-login">
    <div class="page-header"><?= Html::encode($this->title) ?></div>
    <div class="row">
        <div class="col-lg-4">
            <a href="<?= Url::to(['/cms/article/update']) ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> เขียนบทความ</a>
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
                    <?php
                    echo $form->field($model, 'cid')->dropDownList(Categories::makeDropDown($model->langs), [ 'style' => 'margin-right: 10px; width: 250px;', 'onchange' => 'form.submit();']);
                    //$langOption = new \appCMS\language\LanguageCms();
                    echo $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), [ 'style' => 'width: 120px;', 'onchange' => 'form.submit();']);
                    ?>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-lg-12">
                    <?= $form->field($model, 'search')->input('text', [ 'style' => 'width: 300px']); ?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', [ 'class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div><br/>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $model->search($sess),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '50%'],
                'header' => 'ชื่อเรื่อง',
                'attribute' => 'title',
            ],
            [
                'headerOptions' => ['width' => '26%'],
                'header' => 'หมวดหมู่',
                'value' => function($model) {
            return $model->getCatName($model->cid);
        },
            ],
            [
                'headerOptions' => ['width' => '5%', 'style' => 'text-align:center;'],
                'contentOptions' => [ 'align' => 'center'],
                'class' => 'yii\grid\CMSColumn',
                'template' => '{published}',
                'header' => 'แสดง',
                'visible' => Yii::$app->user->can('Publisher')
            ],
            [
                'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                'contentOptions' => [ 'align' => 'center'],
                'class' => 'yii\grid\CMSColumn',
                'template' => '{up} {down}',
                'header' => 'เรียง',
                'visible' => Yii::$app->user->can('Administrator')
            ],
            [
                'headerOptions' => ['width' => '7%', 'style' => 'text-align:center;'],
                'contentOptions' => [ 'align' => 'center'],
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{update} {delete}',
                'header' => 'แก้ไข',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return $model->checkOwner($model->id) == true ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url) : '';
                    },
                    'delete' => function ($url, $model) {
                        return $model->checkOwner($model->id) == true ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['data-pjax'=>0,'data-method'=>'post', 'data-confirm'=>'Are you sure you want to delete this item?']) : '';
                    },
                ],
            ],
        ]
    ]);
    Pjax::end();
    ?> 
</div>
