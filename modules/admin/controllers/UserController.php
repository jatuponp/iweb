<?php

namespace app\modules\admin\controllers;

use app\models\User;

\Yii::$app->name = '<i class="glyphicon glyphicon-user"></i> บริหารข้อมูลผู้ใช้';

class UserController extends \yii\web\Controller {

    public function actionIndex() {
        $model = new User;
        if ($model->load($_POST)) {
            $model->search = $_POST['Content']['search'];
        }
        return $this->render('index', ['model' => $model]);
    }

}
