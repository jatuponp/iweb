<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2elRTE\yii2elRTE;
use app\models\Categories;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Article Management';
?>
<div class="article-content">
    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => '', 'enctype' => 'multipart/form-data'],
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
            <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn', 'onclick' => 'history.back();']) ?>
        </div>
    </div>    
    <div class="row">
        <div class="col-lg-8">

            <?= $form->field($model, 'title')->input('text', ['placeholder' => 'พิมพ์ชื่อเรื่องที่นี้']) ?>
            <div class="form-group required" style="padding-left: 0px; padding-right: 10px;">
                <label>เนื้อหาทั้งหมด (ขนาดภาพ: 390 x 293 หรือ Scale 4:3)</label>
                <?php
                $url1 = Yii::$app->getAssetManager()->publish(Yii::getAlias('@yii2elRTE'));
                if (Yii::$app->user->can('Administrator')) {
                    $folder = '';
                } else {
                    $folder = "Edit_" . Yii::$app->user->identity->gid;
                }
//                echo yii2elRTE::widget(
//                        array(
//                            'model' => $model,
//                            'modelName' => 'Article',
//                            'attribute' => 'fulltexts',
//                            'baseUrl' => $url1[1],
//                            'folder' => $folder,
//                        )
//                );

                //Yii::$app->session->set('KCFINDER', ['disabled' => false, 'uploadURL' => Yii::getAlias('@web') . '/images',]);
                echo $form->field($model, 'fulltexts')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'full'
                ]);
                echo "<br/>";
                ?>
                <div class="help-block"></div>
            </div>
            <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
            <?= $form->field($model, 'langs', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'langs')->dropDownList(\app\models\tblLangs::makeDropDown(), ['style' => 'width: 140px;', 'disabled' => 'disabled', 'onchange' => 'form.submit();']); ?>
            <?= $form->field($model, 'cid')->dropDownList(Categories::makeDropDown($model->langs), ['style' => 'margin-right: 10px; width: 230px;']); ?>
            <?php
            echo $form->field($model, 'published')->widget(SwitchInput::classname(), [
                'pluginOptions' => [
                    'size' => 'normal',
                ],
                'inlineLabel' => false,
            ]);
            echo $form->field($model, 'sci')->checkbox();
            echo $form->field($model, 'isc')->checkbox();
            echo $form->field($model, 'nbs')->checkbox();
            echo $form->field($model, 'la')->checkbox();
            echo $form->field($model, 'startdate')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'เริ่มวันที่', 'style' => 'width: 160px;'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]);
            echo $form->field($model, 'finishdate')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'สิ้นสุดวันที่', 'style' => 'width: 160px;'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]);
            ?>    
        </div>
    </div>
    <?php
    if (Yii::$app->user->can('Administrator')) {
        ?>
        <div class="row">
            <?php
            echo $form->field($model, 'upload_files[]')->widget(FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'showPreview' => true,
                    'showUpload' => false,
                    'showCaption' => false,
                    'uploadClass' => 'btn btn-info',
                    'removeClass' => 'btn btn-danger',
                    'elCaptionText' => '#customCaption'
                ]
            ]);
            ?>
            <div class="file-input">
                <div class="file-preview-thumbnails">            
                    <?php
                    $mPath = \Yii::getAlias('@webroot') . '/images/article/news_' . $model->id;
                    $mUrl = \Yii::getAlias('@web') . '/images/article/news_' . $model->id;
                    if (!is_dir($mPath)) {
                        \yii\helpers\BaseFileHelper::createDirectory($mPath);
                    }
                    foreach (scandir($mPath) as $img) {
                        if ($img != '.' && $img != '..' && $img != 'thumb') {
                            $mThumb = $mUrl . '/thumb/' . $img;
                            //ตรวจสอบภาพตัวอย่าง ว่าถูกสร้างขึ้นมาหรือยัง
                            if (!file_exists($mThumb)) {
                                //ตรวจสอบโฟลเดอร์ภาพตัวอย่าง
                                if (!is_dir($mPath . '/thumb')) {
                                    \yii\helpers\BaseFileHelper::createDirectory($mPath . '/thumb/');
                                }
                                //สร้างภาพตัวอ่ย่าง
                                $image = \Yii::$app->image->load($mPath . '/' . $img);
                                $image->resize(250, 250);
                                $image->save($mPath . '/thumb/' . $img);
                            }
                            echo '<div class="file-preview-frame">';
                            echo '<div class="close fileinput-remove text-right"><a href="' . Url::to(['article/delimage', 'id' => $model->id, 'file' => $img]) . '">×</a></div>';
                            echo '<img src="' . $mThumb . '" class="file-preview-image"/>';
                            echo '</div>';
                        }
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>

