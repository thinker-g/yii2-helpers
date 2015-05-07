<?php
namespace thinker_g\Helpers\controllers;

use Yii;
use yii\filters\VerbFilter;

/**
 * Add CRUD actions based on ModelViewController
 * @author Thinker_g
 */
class CrudController extends ModelViewController
{

    /**
     * @inheritdoc
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all target models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject($this->getModelClass('search'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($this->viewID, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single target model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render($this->viewID, [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new target model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::createObject($this->getModelClass('model'));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->primaryKey]);
        } else {
            return $this->render($this->viewID, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing target model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->primaryKey]);
        } else {
            return $this->render($this->viewID, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing target model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}

?>