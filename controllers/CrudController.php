<?php
namespace thinker_g\Helpers\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

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
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject($this->getModelClass(static::KEY_SEARCH));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($this->viewID, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single target model.
     * @return string
     */
    public function actionView()
    {
        $pk = static::getRequestedPk($this->getModelClass());
        return $this->render($this->viewID, [
            'model' => $this->findModel($pk),
        ]);
    }

    /**
     * Creates a new target model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return \yii\web\Response|string
     */
    public function actionCreate()
    {
        $model = Yii::createObject($this->getModelClass());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(ArrayHelper::merge(['view'], $model->getPrimaryKey(true)));
        } else {
            return $this->render($this->viewID, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing target model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return \yii\web\Response|string
     */
    public function actionUpdate()
    {
        $model = $this->findModel(static::getRequestedPk($this->getModelClass()));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(ArrayHelper::merge(['view'], $model->getPrimaryKey(true)));
        } else {
            return $this->render($this->viewID, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing target model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
        $this->findModel(
            static::getRequestedPk($this->getModelClass())
        )->delete();
        return $this->redirect(['index']);
    }

    /**
     * Get primary key array from the request according to the model specified in the controller/action.
     * This method is built for CRUD operations of models who have composed primary keys. It will try to call static
     * method [[primaryKey()]] of the model, to get an array of attribute names those compose the primary key. Then
     * retrieve the values, whose name is in the primary key array, from "Query Params"(GET parameters).
     * If method [[primaryKey()]] doesn't exist, "id" will be used for default primary key.
     * Any desired primary key missing in the request will cause a BadRequestHttpException.
     *
     * @param string|array $classConf
     * @throws BadRequestHttpException
     * @return array Array contains model primary key, where keys are attribute names values are primary key values.
     * If provided class doesn't have method [[primaryKey()]], "id" will be used as the primary key attribute name. 
     */
    public static function getRequestedPk($classConf)
    {
        $classConf = static::classNameFromConf($classConf);
        $reflector = new \ReflectionClass($classConf);
        if ($reflector->hasMethod('primaryKey')) {
            $keyNames = $classConf::primaryKey();
        } else {
            $keyNames = ['id'];
        }
        foreach ($keyNames as $key) {
            if (is_null($value = Yii::$app->getRequest()->get($key))) {
                throw new BadRequestHttpException('Missing required parameters: ' . $key);
            } else {
                $keys[$key] = $value;
            }
        }
        return $keys;
    }
}

?>