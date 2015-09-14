<?php
namespace hotelja\iugu\services;

use yiibr\brvalidator\CpfValidator;
use yiibr\brvalidator\CnpjValidator;

class AccountData extends \yii\base\Model
{
    public $price_range;
    public $physical_products=false;
    public $business_type;
    public $person_type;
    public $automatic_transfer=true;
    public $cnpj;
    public $company_name;
    public $cpf;
    public $name;
    public $address;
    public $cep;
    public $city;
    public $state;
    public $telephone;
    public $resp_name;
    public $resp_cpf;
    public $bank;
    public $bank_ag;
    public $account_type;
    public $bank_cc;

    const PRICE_RANGE_LOW='Até R$ 100,00';
    const PRICE_RANGE_MEDIUM='Entre R$ 100,00 e R$ 500,00';
    const PRICE_RANGE_HIGH='Mais que R$ 500,00';

    const PERSONTYPE_JURIDICA='Pessoa Jurídica';
    const PERSONTYPE_FISICA='Pessoa Jurídica';

    const ACCOUNTTYPE_CORRRENTE='Corrente';
    const ACCOUNTTYPE_POUPANCA='Poupança';

    const BANK_ITAU='Itaú';
    const BANK_BRADESCO='Bradesco';
    const BANK_CAIXA='Caixa Econômica';
    const BANK_BB='Banco do Brasil';
    const BANK_SANTANDER='Santander';

    public function requestVerification($id)
    {
        return $this->post($id.'/request_verification');
    }

    public function rules()
    {
        return [
            [['price_range','automatic_transfer','address','cep','city','state','telephone','bank','bank_ag','account_type','bank_cc'],'required','on'=>'request_verification'],
            //string
            [['company_name','business_type','person_type','name','resp_name','city','state',],'string','max'=>255],
            [['address',],'string','max'=>512],
            [['bank_ag',],'string','max'=>12],
            //bool
            [['physical_products','automatic_transfer'],'boolean'],
            [['automatic_transfer'],'default','value'=>true],
            //ranges
            [['price_range'],'in','range'=>array_keys(self::getPriceRanges())],
            [['person_type'],'in','range'=>array_keys(self::getPersonTypes())],
            [['bank'],'in','range'=>array_keys(self::getBanks())],
            [['account_type'],'in','range'=>array_keys(self::getAccountTypes())],
            //custom
            [['cnpj'], CnpjValidator::className()],
            [['cpf','resp_cpf'], CpfValidator::className()],
            //conditional
            [['cnpj','company_name','resp_name','resp_cpf'],'when'=>function($model){
                return $model->person_type===self::PERSONTYPE_JURIDICA;
            }],
            [['cpf','name',],'when'=>function($model){
                return $model->person_type===self::PERSONTYPE_FISICA;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price_range'=>'Valor máximo da venda',
            'physical_products'=>'Vende produtos físicos?',
            'business_type'=>'Descrição do negócio',
            'person_type'=>'Tipo de pessoa',
            'automatic_transfer'=>'Saque automático',
            'cnpj'=>'CNPJ',
            'company_name'=>'Nome da Empresa',
            'cpf'=>'CPF',
            'name'=>'Nome',
            'address'=>'Endereço',
            'cep'=>'CEP',
            'city'=>'Cidade',
            'state'=>'Estado',
            'telephone'=>'Telefone',
            'resp_name'=>'Nome do Responsável',
            'resp_cpf'=>'CPF do Responsável',
            'bank'=>'Banco',
            'bank_ag'=>'Agência da Conta',
            'account_type'=>'Tipo da conta',
            'bank_cc'=>'Número da Conta',
        ];
    }

    /**
     * @return array|string
     */
    public static function getPriceRanges($value=null)
    {
        $list=[
            self::PRICE_RANGE_LOW=>self::PRICE_RANGE_LOW,
            self::PRICE_RANGE_MEDIUM=>self::PRICE_RANGE_MEDIUM,
            self::PRICE_RANGE_HIGH=>self::PRICE_RANGE_HIGH,
        ];
        if($value!==null)
            return $list[$value];
        return $list;
    }

    /**
     * @return array|string
     */
    public static function getPersonTypes($value=null)
    {
        $list=[
            self::PERSONTYPE_FISICA=>self::PERSONTYPE_FISICA,
            self::PERSONTYPE_JURIDICA=>self::PERSONTYPE_JURIDICA,
        ];
        if($value!==null)
            return $list[$value];
        return $list;
    }

    /**
     * @return array|string
     */
    public static function getAccountTypes($value=null)
    {
        $list=[
            self::ACCOUNTTYPE_CORRRENTE=>self::ACCOUNTTYPE_CORRRENTE,
            self::ACCOUNTTYPE_POUPANCA=>self::ACCOUNTTYPE_POUPANCA,
        ];
        if($value!==null)
            return $list[$value];
        return $list;
    }

    /**
     * @return array|string
     */
    public static function getBanks($value=null)
    {
        $list=[
            self::BANK_ITAU=>self::BANK_ITAU,
            self::BANK_BRADESCO=>self::BANK_BRADESCO,
            self::BANK_CAIXA=>self::BANK_CAIXA,
            self::BANK_BB=>self::BANK_BB,
            self::BANK_SANTANDER=>self::BANK_SANTANDER,
        ];
        if($value!==null)
            return $list[$value];
        return $list;
    }
}
