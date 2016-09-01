<?php

namespace app\components;

class AlertWidget extends \yii\bootstrap\Widget
{
    /**
     * @var Массив конфигурации типов предупреждений для флэш-сообщений.
     * Этот массив настроен как $key => $value, где:
     * - $key это имя переменной сеанса флэш
     * - $value тип оповещения начальной загрузки (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var массив опций для рендеринга кнопки закрытия тега.
     */
    public $closeButton = [];
    public function init()
    {
        parent::init();
        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';
        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    /* инициализировать класс CSS для каждого окна предупреждения */
                    $this->options['class'] = $this->alertTypes[$type] . $appendCss;
                    /* присвоить уникальный идентификатор каждому окну предупреждений */
                    $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;
                    echo \yii\bootstrap\Alert::widget([
                        'body' => $message,
                        'closeButton' => $this->closeButton,
                        'options' => $this->options,
                    ]);
                }
                $session->removeFlash($type);
            }
        }
    }
}