<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Login\Model\Login;
use Login\Form\LoginForm;       // <-- Add this import
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService as AuthenticationService;
use Zend\Authentication\Result as Result;
use Zend\Session\Container;


class LoginController extends AbstractActionController {
 
    protected $loginTable;
    protected $authservice;
    
    public function indexAction() {
        
        $form = new LoginForm();
        $form->get('submit')->setValue('Login');
        
        $messages = null;
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $login = new Login();
            $form->setInputFilter($login->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                $login->exchangeArray($form->getData());
                
                $data = $form->getData();
                
                //check authentication...
                $sm = $this->getServiceLocator();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                
                $authAdapter = new AuthAdapter($dbAdapter);
           
                $authAdapter->setTableName('login')
                            ->setIdentityColumn('user_name')
                            ->setCredentialColumn('password')
                            ->setCredentialTreatment("SHA1(?)");
        
                $authAdapter->setIdentity($data['user_name'])
                            ->setCredential($data['password']);
            
                //print_r($authAdapter);echo "gaurav";die;       
                
                $auth = new AuthenticationService();
                
                //print_r($authAdapter);die;
                
                $result = $auth->authenticate($authAdapter);
                switch($result->getCode()){
                    case Result::FAILURE_CREDENTIAL_INVALID;
                        break;
                    case Result::FAILURE_IDENTITY_NOT_FOUND;
                        break;
                    case Result::SUCCESS;
                        $storage = $auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(null,'password'));
                        $user = new Container('user');
                        $user->name = $authAdapter->getResultRowObject(null,'password')->user_name;
                        $user->id = $authAdapter->getResultRowObject(null,'password')->id;
                        $time = 1209600;
                        if($data['rememberme']){
                            $sessionManager = new \Zend\Session\SessionManager();
                            $sessionManager->rememberMe($time);
                        }
                        return $this->redirect()->toRoute('member', array('action' => 'myaccount'));
                       break;
                    default:
                        break;
                }
                 foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $messages = "{$message}\n";
                }
            }
        }
           
           return array('form' => $form,'messages'=>$messages);
    }

    
    public function getLoginTable() {
        if (!$this->loginTable) {
            $sm = $this->getServiceLocator();
            $this->loginTable = $sm->get('Login\Model\LoginTable');
        }
        return $this->loginTable;
    }
    
 
        public function logoutAction()
    {
        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();
        $sessionManager->destroy();
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

}
