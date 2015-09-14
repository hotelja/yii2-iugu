<?php
namespace hotelja\iugu\services;

class Accounts extends \yii\base\Model
{
    public $id;
    public $cpf;
    public $activity;
    public function rules()
    {
        return [
            [['id'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['cpf'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['activity'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'Documento (RG, CPF)',
            'cpf'=>'CPF (Caso não tenha CPF no id)',
            'activity'=>'Um documento que comprove a atividade exercida pela empresa/pessoa da conta',
        ];
    }

    public function afterValidate()
    {
        if(!$this->hasErrors)
        {
            foreach($this->getAttributes() as $attr=>$file)
            {
                if($file && !$file->hasError)
                    $this->$attr='@'.$file->tempName;
                else
                    unset($this->$attr);
            }
        }
        return parent::afterValidate();
    }
}
