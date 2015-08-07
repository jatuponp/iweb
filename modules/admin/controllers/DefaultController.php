<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Sitecounter;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $model = new Sitecounter();
        if ($model->load($_POST)) {
            $sess = array();
            $sess['year'] = $_POST['Sitecounter']['year'];
            Yii::$app->session->set('sessStat', $sess);
        }
//
        $sess = Yii::$app->session->get('sessStat');
        $model->year = $sess['year'];
        if(!$model->year){
            $model->year = date('Y');
        }
        return $this->render('index', ['model' => $model]);
    }
}
