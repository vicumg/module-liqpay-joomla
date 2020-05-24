<?php
/**
 * Helper class for Liqpay! module
 *
 *
 * Hello World! Module Entry Point
 *
 * @package    vicumg
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * payment button by liqpay Api
 */



class ModLiqpayHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */

    public static function getLiqpay($params)
    {

        $data ['private_key']=$params->get('private_key');
        $data ['public_key']=$params->get('public_key');
        $data ['description']=$params->get('description');
        $data ['order_id'] ='';
        $data['amount'] = $params->get('amount');

        $send_data = self::getLiqPayData($data);


        return  self::getEncodeData($send_data, $private_key);
    }


    public static function getEncodeData($send_data, $private_key)
    {

        $json_data = json_encode($send_data);

        $liqpay_data = base64_encode($json_data);

        $liqpay_signature = base64_encode(sha1($private_key . $liqpay_data . $private_key,true));


        $formdata = ['liqpay_data'=>$liqpay_data,
                     'signature'=>$liqpay_signature,
                    ];

        return $formdata;
    }

    public static function getAjax()
    {

        $app = JFactory::getApplication();

        $input = $app->input;

        $ammount = (int)$input->get('summ');
        $orderId = (int)$input->get('order_id');

        $module = JModuleHelper::getModule('mod_liqpay');

        $params = new JRegistry();
        $params->loadString($module->params);


        $data ['private_key']=$params->get('private_key');
        $data ['public_key']=$params->get('public_key');
        $data ['description']=$params->get('description') . ' - '.$orderId;
        $data ['order_id'] =$orderId;
        $data['amount'] = $ammount;

        $send_data = self::getLiqPayData($data);


        $formdata = self::getEncodeData($send_data, $data ['private_key']);


        return $formdata;
    }

    public static function getLiqPayData($data)
    {
        $private_key = $data ['private_key'];
        $public_key = $data ['public_key'];
        $description = $data ['description'];
        $orderId = $data ['order_id'];
        $amount = $data ['amount'];
        $version  = '3';

        $send_data = array(
            'version'    => $version,
            'public_key'  => $public_key,
            'private_key'  => $private_key,
            'action'  => 'pay',
            'amount'      => $amount,
            'currency'    => 'UAH',
            'description'    => $description,
            'result_url'  => '',
            'order_id'  => $orderId);
        return $send_data;
    }

}
