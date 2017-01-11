<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\i18n\Formatter as BaseFormatter;
use app\models\Transaction;

class Formatter extends BaseFormatter
{
    public $transactionStatus = ['Pending'];
    public $transactionType = ['Impression', 'Click', 'Lead', 'Click Out', 'Sale'];

    public function asTransactionStatus($value)
    {
        if ($this->transactionStatus[$value] === null) {
            return $this->nullDisplay;
        }
        return Html::tag('span', $this->transactionStatus[$value], [
        	'class' => 'label label-default'
        ]);
    }

    public function asTransactionType($value)
    {
        if ($this->transactionType[$value] === null) {
            return $this->nullDisplay;
        }
        if ($value === Transaction::TYPE_SALE || $value === Transaction::TYPE_LEAD) {
            return Html::tag('span', $this->transactionType[$value], [
                'class' => 'label label-primary'
            ]);
        }
        return $this->transactionType[$value];
    }
}
