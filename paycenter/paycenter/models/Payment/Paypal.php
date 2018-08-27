<?php if (!defined('ROOT_PATH')){exit('No Permission');}
 use Omnipay\Omnipay; 


/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_Paypal extends Yf_Model
{

    /**
     * Constructor
     *
     * @param  array $payment_row 支付平台信息
     * @param  array $order_row 订单信息
     * @access public
     */
    public function __construct($payment_row = array(), $order_row = array())
    {

        $base_url =  Yf_Registry::get('base_url');
        $shop_url =  Yf_Registry::get('shop_api_url');
        $this->payment['Username'] = $payment_row['Username'];  //商户账号
        $this->payment['Password']    = $payment_row['Password'];      //密码
        $this->payment['Signature']    = $payment_row['Signature'];      //签名
        $this->payment['return_url'] =$base_url. "/paycenter/api/payment/paypal/notify_url.php"; //返回URL
        $this->payment['cancelUrl'] = $shop_url."/index.php?ctl=Buyer_Order&met=physical"; //取消URL
    }
     public function getGateway()
    {   
         require_once ROOT_PATH.'/vendor/autoload.php';
         $gateway = Omnipay::create('PayPal_Express');
         $gateway->setUsername($this->payment['Username']);
         $gateway->setPassword($this->payment['Password']);
         $gateway->setSignature($this->payment['Signature']);   
         $gateway->setTestMode(true);
         
         return $gateway;
    }


    public function pay($order_row = array())
    {
         $gateway=$this->getGateway();
         //var_dump($gateway);die;
         $params=$this->params($order_row);

        try {
            $response = $gateway->purchase($params)->send();

            if ($response->isSuccessful()) {

               exit($response->getRedirectData());

            } elseif ($response->isRedirect()) {

                $response->redirect();

            } else {
                // display error to customer
                exit($response->getMessage());
            }
        } catch (\Exception $e) {
            exit('Sorry, there was an error processing your payment. Please try again later.');
        }
    }

    public function params($order_row=array())
    {

        $params = array(
        'amount' => $order_row['union_online_pay_amount'],
        'transactionId'=>$order_row['union_order_id'],
        'description'=>$order_row['trade_title'],
        'clientIp'=>ip(),
        'currency' => 'USD',
        'returnUrl' => $this->payment['return_url'],
        'cancelUrl' => $this->payment['cancelUrl'],
        );
        Session_start();
        $_SESSION['params']=$params;

        return $params;
    }
   

    public function getReturnUrl()
    {   
        
        require_once ROOT_PATH.'/vendor/autoload.php';
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername('1730154793-shop_api1.qq.com');
        $gateway->setPassword('9PU9443BE3ELCVPL');
        $gateway->setSignature('ASF70zltkxECKkEhGr2c-kpo7VzaAGcJndBmJ-pKDx2rqoIk56GqMjPL');   
        $gateway->setTestMode(true);   
        // $gateway=$this->getGateway();
        //  var_dump($gateway);die;
        Session_start();
        $params=$_SESSION['params'];

        $response = $gateway->completePurchase($params)->send();
      
        $paypalResponse = $response->getData();
        return $paypalResponse;
    }

}

?>