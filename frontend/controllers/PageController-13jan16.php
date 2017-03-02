<?php
namespace frontend\controllers;

use Yii;
use common\models\CmsPage;
use yii\web\Controller;
use yii\behaviors\SluggableBehavior;
use yii\web\Session;

/**
 * StateController implements the CRUD actions for State model.
 */
class PageController extends Controller
{
    public function behaviors()
{
    return [
        [
            'class' => SluggableBehavior::className(),
            'attribute' => 'page_title',
           // 'immutable' => true,
           // 'ensureUnique'=>true,
          //  'slugAttribute' => 'alias',
        ],
    ];
}

    /**
     * Displays a single Status model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
     
    /**
     * Displays a single Status model.
     * @param string $slug
     * @return mixed
     */
    public function actionSlug($slug)
    { 
      $model = CmsPage::find()->where(['slug'=>$slug])->one();
     
      if (!is_null($model)) {
          return $this->render('view', [
              'model' => $model,
          ]);      
      } else {
        return $this->redirect(['/site/index']);
      }
    }
}
