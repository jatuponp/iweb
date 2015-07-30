<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_article".
 *
 * @property integer $id
 * @property integer $cid
 * @property string $title
 * @property string $fulltexts
 * @property integer $ordering
 * @property integer $published
 * @property string $startdate
 * @property string $finishdate
 * @property string $submitdate
 * @property string $applydate
 * @property string $langs
 * @property string $pins
 * @property integer $frontpage
 */
class Article extends \yii\db\ActiveRecord {

    public $search;
    public $upload_files;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_article';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cid', 'title'], 'required'],
            [['cid', 'ordering', 'published', 'frontpage', 'sci', 'nbs', 'la', 'isc'], 'integer'],
            [['fulltexts', 'langs', 'search'], 'string'],
            [['startdate', 'finishdate', 'submitdate', 'applydate'], 'safe'],
            [['title'], 'string', 'max' => 300],
            [['pins'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cid' => 'หมวดหมู่บทความ',
            'title' => 'ชื่อเรื่อง',
            'fulltexts' => 'Fulltexts',
            'ordering' => 'Ordering',
            'published' => 'การเผยแพร่',
            'startdate' => 'เริ่มวันที่',
            'finishdate' => 'สิ้นสุดวันที่',
            'submitdate' => 'Submitdate',
            'applydate' => 'Applydate',
            'langs' => 'ภาษา',
            'pins' => 'Pins',
            'sci' => 'คณะวิทยาศาสตร์ประยุกต์และวิศวกรรมศาสตร์',
            'nbs' => 'คณะบริหารธุรกิจ',
            'la' => 'คณะศิลปศาสตร์',
            'isc' => 'คณะสังคมศาสตร์บูรณาการ',
            'frontpage' => 'Frontpage',
            'upload_files' => 'ภาพประกอบ'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
                if ($auth['Editor']->roleName == 'Editor') {
                    $this->published = 0;
                }
                $this->submitdate = $now;
                $this->applydate = $now;
                $this->frontpage = 0;
                $this->createBy = \Yii::$app->user->id;
                if(!$this->startdate){
                    $this->startdate = '0000-00-00';
                }
                if(!$this->finishdate){
                    $this->finishdate = '0000-00-00';
                }
                if (!$this->langs) {
                    $this->langs = 'thai';
                }
            } else {
                $this->applydate = $now;
            }
            return true;
        }
        return false;
    }

    public function getCatName($cid) {
        $cat = Categories::findOne($cid);
        return $cat->title;
    }

    public function search() {
        $search = $this->search; //($_POST['Article']['search'])? $_POST['Article']['search']:($_REQUEST['search'])? $_REQUEST['search']:'';
        $langs = $this->langs;
        $cid = $this->cid;
        $query = Article::find();
        if ($search)
            $query->where('title LIKE :s', [':s' => "%$search%"]);

        if ($langs)
            $query->andWhere(['langs' => $langs]);
        if ($cid)
            $query->andWhere(['cid' => $cid]);

        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
        if ($auth['Editor']->roleName == 'Editor' || $auth['Publisher']->roleName == 'Publisher') {
            $q = User::find()->where(['gid' => \Yii::$app->user->identity->gid])->all();
            foreach ($q as $r) {
                $gid[] = $r->id;
            }
            $query->andWhere('createBy IN (' . implode(',', $gid) . ')');
        }

        $query->orderBy('cid, ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public function news($cid = null) {
        $langs = $this->langs;
        $now = date('Y-m-d');
        $query = Article::find()->where(['cid' => $cid, 'published' => 1]);
        $query->andWhere(['OR', 'startdate = ' . "'0000-00-00'", "startdate <='" . $now . "'"]);
        $query->andWhere(['OR', 'finishdate = ' . "'0000-00-00'", "finishdate >='" . $now . "'"]);
        if ($langs)
            $query->andWhere(['langs' => $langs]);

        $query->orderBy('ordering ASC');
        $result = $query->all();

        return $result;
    }

    public function checkOwner($id) {
        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
        //print_r($auth);
        $access = false;
        if ($auth['Editor']->roleName == 'Editor') {
            $model = Article::findOne($id);
            if ($model->createBy == \Yii::$app->user->id) {
                $access = true;
            }
        } else {
            $access = true;
        }
        return $access;
    }

    public function orderMax($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Article::find()->where(['langs' => $langs, 'cid' => $cid])->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Article::find()->where(['langs' => $langs, 'cid' => $cid])->min('ordering');
        return $query;
    }

    public function makeLink($langs = null) {
        global $data;
        $langs = ($langs) ? $langs : 'thai';
        $data = array();
        $parents = Article::find()
                ->where(['langs' => $langs, 'published' => 1])
                ->orderBy('ordering ASC')
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[\yii\helpers\Url::to(['/site/content', 'id' => $parent->id])] = '- ' . $parent->title;
        }

        return $data;
    }

}
