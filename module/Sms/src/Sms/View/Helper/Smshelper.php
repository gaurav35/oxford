<?php
namespace Sms\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\Client;

class Smshelper extends AbstractHelper
{
        
    private $url = "http://bulksms.mysmsmantra.com:8080/WebSMS/";
    private $username = "Mukesh551";
    private $password = "LAXMI.123";
    private $sendername = "OESVNS";
    
     public function smsbalance(){
          $client = new Client("{$this->url}balance.jsp?username={$this->username}&password={$this->password}");
          $response = $client->send();   
          return $response->getContent();
               
   }
   
   public function smsgetcampaigndetail() {

           $client = new Client("{$this->url}getsmscampaign.jsp?username={$this->username}&password={$this->password}&status=all");
           $response = $client->send();   
           return $response->getContent();
           
   }
   
   public function sendBulkSms($mobile,$message) {
       try{  
           $client = new Client("{$this->url}SMSAPI.jsp?username={$this->username}&password={$this->password}&sendername={$this->sendername}&mobileno={$mobile}&message=" . urlencode($message));
           $response = $client->send();
           return $response->getContent();
           }catch(Exception $e){
               return $e->getMessages();
           }
   }
}

