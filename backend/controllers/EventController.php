<?php

namespace backend\controllers;

use backend\models\Event;
use backend\models\EventSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Provincia;
use backend\models\Municipio;
use backend\models\Comuna;
use backend\models\User;
use backend\models\Contacto;
use Yii;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller {

    /**
     * @inheritDoc
     */
    public function behaviors() {
        return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ]
        );
    }

    /**
     * Lists all Event models.
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListaeventos() {
        // Busca todos os eventos do banco de dados
        $eventos = \backend\models\Event::find()->all();
//             $eventos= $this->actionGetEvents();   
        // Renderiza a visualização index.php e passa os eventos para ela
        return $this->render('listaeventos', [
                    'eventos' => $eventos,
        ]);
    }

    public function actionGetEvents() {
        $entidadesSelecionadas = Yii::$app->request->get('entidades');
        $provinciasSelecionadas = Yii::$app->request->get('provincias');
        $areasSelecionadas = Yii::$app->request->get('areas');
        $dataInicio = Yii::$app->request->get('dataInicio');
        $dataFim = Yii::$app->request->get('dataFim');

        // Obter eventos filtrados
        $events = Event::getFilteredEvents($entidadesSelecionadas, $provinciasSelecionadas, $areasSelecionadas, $dataInicio, $dataFim);

//    return $events;
        return $this->render('listaeventos', ['eventos' => $events]);
    }

    /**
     * Displays a single Event model.
     * @param int $Id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($Id) {
        return $this->render('view', [
                    'model' => $this->findModel($Id),
        ]);
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new Event();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'Id' => $model->Id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $Id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($Id) {
        $provincias = Provincia::find()->all();
        $provinciasList = [];
        $limit = 4; // Defina o número de elementos que deseja manter      
        $isAdmin = Yii::$app->user->isGuest ? false : Yii::$app->user->can("Permissão de Administrador");
        $nomeLogado = Yii::$app->user->identity->nomeCompleto;
        $emailLogado = Yii::$app->user->identity->email;
        $model = $this->findModel($Id);
        $participantes = $model->participantes;
        $titulo = $model->summary;
        // Transformar array de participantes em uma string formatada
        // Verificar se $model->participantes é um array antes de chamar implode
        if (is_array($model->participantes)) {
           $participantesString = implode(',', $model->participantes);
            $model->participantes = $participantesString;
        }
//        $participantesString = implode(',', $model->participantes);
//        $model->participantes = $participantesString;
        // Suponha que $model->start tenha o formato 'Y-m-d H:i:s'
        $dateTimeString = $model->start;
        $dataevento = date('d-m-Y', strtotime($dateTimeString)); // Obtém a data no formato 'dia-mês-ano'
        $anfitriaoNome = $model->convocadoPor;
        if ($isAdmin || ($nomeLogado == $model->convocadoPor)) {
            foreach (array_slice($provincias, 0, $limit) as $provincia) {
                $provinciasList[$provincia->Id] = $provincia->nomeProvincia;
            }
            if ($this->request->isPost && $model->load($this->request->post())) {
                if (is_array($model->participantes)) {
                $participantesString = implode(',', $model->participantes);
                $model->participantes = $participantesString;
            }
                $anfitriaoEmail = User::find()->select('email')
                        ->where(['nomeCompleto' => $anfitriaoNome])
                        ->scalar();
                if ($model->save()) {
                     $participantesArray = explode(',', $model->participantes);
                    //  Enviar notificação por email ao anfitrião original do evento
                    if (($anfitriaoEmail !== null) && ($emailLogado == $anfitriaoEmail)) {
                        Yii::$app->mailer->compose()
                                ->setTo(trim($anfitriaoEmail))
                                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                                ->setSubject('Actualização de Evento')
                                ->setHtmlBody("Olá $anfitriaoNome,<br><br> Actualizou o evento do <b>Calendário</b> para o dia <b>$dataevento,</b> com o nome <b>[$titulo].</b><br><br>  Para mais detalhes, clique <a href=\"https://sgi-fresancamoes.com/admin/calendario\">aqui.</a><br><br> Continuação de bom trabalho,<br><br> SGI FRESAN/Camões, I.P.")
                                ->send();
                    }
                    // Email ao Administrador que actualizou o evento
                    else if (($emailLogado !== null)) {
                        Yii::$app->mailer->compose()
                                ->setTo(trim($emailLogado))
                                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                                ->setSubject('Actualização de Evento')
                                ->setHtmlBody("Olá $nomeLogado,<br><br> Actualizou o evento do <b>Calendário</b> para o dia <b>$dataevento,</b> com o nome <b>[$titulo].</b><br><br>  Para mais detalhes, clique <a href=\"https://sgi-fresancamoes.com/admin/calendario\">aqui.</a><br><br> Continuação de bom trabalho,<br><br> SGI FRESAN/Camões, I.P.")
                                ->send();
                        // Email ao anfitrião que não actualizou o evento
                        Yii::$app->mailer->compose()
                                ->setTo(trim($anfitriaoEmail))
                                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                                ->setSubject('Evento Actualizado')
                                ->setHtmlBody("Olá $anfitriaoNome,<br><br>Foi actualizado pela administração o evento <b>[$titulo]</b> adicionado por si ao <b>Calendário</b>, para o dia <b>$dataevento,</b> com .</b><br><br>  Para mais detalhes, clique <a href=\"https://sgi-fresancamoes.com/admin/calendario\">aqui.</a><br><br> Continuação de bom trabalho,<br><br> SGI FRESAN/Camões, I.P.")
                                ->send();
                    }
                    // Enviar notificações por email para os participantes
                    foreach ($participantesArray as $email) {
                        // Consulta para encontrar o nome do contacto com o email atual
                        $nomeContacto = Contacto::find()
                                ->select('nome')
                                ->where(['email' => $email])
                                ->scalar(); // Retorna o nome do primeiro contacto encontrado ou null se não houver

                        if ($nomeContacto !== null) {
                            Yii::$app->mailer->compose()
                                    ->setTo(trim($email))
                                    ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                                    ->setSubject('Actualização de Evento')
                                    ->setHtmlBody("Olá $nomeContacto,<br><br> Foi actualizado o evento <b>[$titulo]</b> convocado por $anfitriaoNome para o dia <b>$dataevento,</b>. <br><br> Aceda ao <b>Calendário</b> para <a href=\"https://sgi-fresancamoes.com/admin/calendario\">mais detalhes.</a><br><br> Continuação de bom trabalho,<br><br> SGI FRESAN/Camões, I.P.")
                                    ->send();
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Evento actualizado e notificações enviadas!');
                    return $this->redirect(['site/calendario']);
                }

                return $this->render('update', [
                            'model' => $model,
                            'provinciasList' => $provinciasList,
                ]);
            }
            return $this->render('update', [
                        'model' => $model,
                        'provinciasList' => $provinciasList,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'So o Administrador ou anfitrião pode alterar eventos');
//                var_dump("Experiencia");
            return $this->redirect(['site/calendario']);
        }
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $Id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($Id) {
        $this->findModel($Id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $Id ID
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($Id) {
        if (($model = Event::findOne(['Id' => $Id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetMunicipios($id) {
        //Yii::$app->response->format = Response::FORMAT_JSON;

        $limite = 10;
        if ($id == 2) {

            $limite = 7;
        }

        $municipios = Municipio::find()
                ->where(['provinciaID' => $id])
                ->limit($limite) // Limita o resultado a três municípios
                ->all();

        $municipios_list = [];
        foreach ($municipios as $municipio) {
            $municipios_list[] = ['id' => $municipio->Id, 'nome' => $municipio->nomeMunicipio];
        }

        return json_encode($municipios_list);
    
    }

    public function actionGetComunas($id) {
        $comunas = Comuna::find()->where(['municipioID' => $id])->all();
        $comunas_list = [];
        foreach ($comunas as $comuna) {
            $comunas_list[] = ['id' => $comuna->Id, 'nome' => $comuna->nomeComuna];
        }
        return json_encode($comunas_list);
    }
}