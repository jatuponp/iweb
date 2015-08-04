<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\AccessControl;
use yii\web\Controller;
use app\models\Categories;
Yii::$app->name = '<i class="glyphicon glyphicon-folder-open"></i> บริหารหมวดหมู่บทความ';

class CategoriesController extends Controller {

    public function actionIndex() {
        $model = new Categories;
        if($model->load($_POST)){
            $model->langs = $_POST['Categories']['langs'];
        }else{
            $model->langs = ($_REQUEST['langs']) ? $_REQUEST['langs'] : 'thai';
        }
        return $this->render('index', ['model' => $model]);
    }
    
    public function actionUpdate($id = null) {
        $model = new Categories;
        if ($model->load($_POST)) {
            $id = $_POST['Categories']['id'];
            if ($id) {
                $model = Categories::findOne($id);
                $model->attributes = $_POST['Categories'];
            }
            if ($model->save()) {
                return $this->redirect(['index','langs'=>$_POST['Categories']['langs']]);
            }else{
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = Categories::findOne($id);
        }else{
            $model->langs = $_REQUEST['langs'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }
    
    public function actionDelete($id){
        $model = Content::find($id);
        $model->delete();
        return $this->redirect(array('index'));
    }

}
