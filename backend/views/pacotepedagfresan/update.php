<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Pacotepedagfresan $model */

$this->title = Yii::t('app', 'Update Pacotepedagfresan: {name}', [
    'name' => $models->Id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pacotepedagfresans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $models->Id, 'url' => ['view', 'Id' => $models->Id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pacotepedagfresan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'models' => $models,
    ]) ?>

</div>