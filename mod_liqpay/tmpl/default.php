<?php
// No direct access
defined('_JEXEC') or die;
$devarray = ['79.110.129.206'];

$ip = trim( JFactory::getApplication()->input->server->get('REMOTE_ADDR', ''));

$isDev = in_array($ip,$devarray);

if ($isDev) {
    $show = 'block';
}else{
    $show = 'none';
}

?>

<div id="liqpay-source">
    <div>
        <img src="https://static.liqpay.ua/logo/liqpay4.png" class="liqpay-logo">
    </div>
    <span id="errorMessage"></span>
    <div class="liqpay-wrapper">
        <input type="number"
               id = "liqpay-order"
               value=""
               class ="liqpay-input"
               placeholder="Номер заявки 7-10 цифр"
               min="0000000" max="9999999999"
        >
        <div class="paymen-summ">
        <input type="number"
               id = "liqpay-summ" value="<?php echo $params->get('amount');?>"
               class ="liqpay-input"
               placeholder="1000 грн"
               min="0"
        >
            <span class="summ-label">Сумма оплаты, грн.</span>
        </div>

        <div class="liqpay-form clear">
            <form method="POST" action="https://www.liqpay.ua/api/3/checkout" accept-charset="utf-8" id = "liqpay-pay">
                <input type="hidden" id  = "liqpay-data" name="data" value="<?php echo $liqpay['liqpay_data'];?>"/>
                <input type="hidden" id  = "liqpay-sign" name="signature" value="<?php echo $liqpay['signature'];?>"/>
                <input type="image" src="//static.liqpay.ua/buttons/p1ru.radius.png"/>
            </form>
        </div>
    </div>
</div>


<script>
    const errors = {
        order:'Ошибка в номер заявки (номер заявки от  7 до 10 цифр)',
        summ:'Введите корректную сумму',
        clear:''
    }


    let itemID ="<?php echo $menu; ?>"

    let source = document.getElementById('liqpay-source');
    let target = document.getElementById('liqpay-button-wrapper');
    let form = source.innerHTML;
    source.innerHTML= '';
    target.innerHTML = form;

    let liqpayForm =  document.getElementById('liqpay-pay');
    let liqpayFormData =  document.getElementById('liqpay-data');
    let liqpayFormSign =  document.getElementById('liqpay-sign');

    const Liqpay={
        form:liqpayForm,
        data:liqpayFormData,
        sign:liqpayFormSign,
    }

    liqpayForm.addEventListener('click',function (event) {
        let summ = document.getElementById('liqpay-summ').value;
        let orderNumber = document.getElementById('liqpay-order').value;
        event.preventDefault();
        if (validate(summ,orderNumber)) {
            showValidateError('clear');
            MakePayment(summ, orderNumber,Liqpay);
        }
    });

    function MakePayment(summ,orderNumber,form) {

        fetch('index.php?option=com_ajax&module=liqpay&format=json&summ='+summ+'&order_id='+orderNumber+'&Itemid='+itemID)
            .then((response) => {

                return response.json();
            })
            .then((data) => {
                formdata = data.data;

                form.data.value = formdata.liqpay_data;
                form.sign.value = formdata.signature;
                form.form.submit();
            });
    }

    function validate(summ,orderNumber){

        if (orderNumber < 999999){
            showValidateError('order');
            return false;
        }
        if (summ == '' || summ =='0'){
          showValidateError('summ');
            return false;
        }
        return true;
    }


    function showValidateError(error) {

        if (error == 'order'){
             document.getElementById('errorMessage').innerText = errors.order;
        }
        if (error == 'summ'){
            document.getElementById('errorMessage').innerText = errors.summ;
        }
        if (error == 'clear'){
            document.getElementById('errorMessage').innerText = errors.clear;
        }
    }

</script>

<style>
    #liqpay-source{
        display: none;
    }
    #liqpay-button-wrapper{
        background-color: #f5f5f5;
        height: auto;
        display: block;
        padding: 20px;
    }
    .liqpay-input{
        display: block;
        width: 150px;
        border: 1px solid #dedede;
        border-radius: 4px;
        padding: 10px;
        margin: 10px;
    }
    .liqpay-form{
        display: inline-block;
        padding: 10px 0 10px 40px;
    }
    .liqpay-form button{
        width:150px;
    }
    .liqpay-form button img{
        height: 20px;
        width: auto;
        display: inline-block;
    }

    #errorMessage{
        display: block;
        margin: 10px;
        color: red;
        font-size: 14px;
    }
    #liqpay-button-wrapper .liqpay-logo{
        width: 200px;
        height: auto;
        display: block;
        margin-left: 50px;
        margin-bottom: 50px;
    }

    #liqpay-summ{
        float: left;
    }
    .paymen-summ{
        height: 70px;
    }
    span.summ-label {
        display: block;
        float: left;
        margin-top: 27px;
    }
    #liqpay-order{
        width: 220px;
    }
    .liqpay-wrapper {
        margin-left: 35px;
    }

    @media screen and (max-width: 768px){
        .liqpay-wrapper {
            margin-left: 0px;
        }
    }

</style>