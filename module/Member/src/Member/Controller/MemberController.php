<?php

namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Member\Form\MemberForm;
use Sms\View\Helper\Smshelper as SmsHelper;

class MemberController extends AbstractActionController {

    protected $smsTable;
    protected $standardTable;
    protected $loginTable;

    public function indexAction() {
        $this->layout()->heading = 'Send SMS Page';
        $message = "";
        $user = new Container('user');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $sms_id = (int) $request->getPost('sms_id_t');
            $sms = $this->getSmsTable()->getSms($sms_id);
            $template = $sms->sms_temp;
            $selected_mobiles = $request->getPost('st_id');

            $selected_mobiles = implode(',', $selected_mobiles);
            $selected_mobiles = array_filter(explode(',', $selected_mobiles));

            if (!empty($selected_mobiles)) {
                $selected_mobiles = implode(',', $selected_mobiles);
                $sendbulksms = new SmsHelper();
                $bulksms = $sendbulksms->sendBulkSms($selected_mobiles, $template);
                $this->flashMessenger()->setNamespace('success')->addMessage($bulksms);
            }
        }
        return new ViewModel(array(
            'sms_template' => $this->getAlbumTable()->fetchAll(),
            'standard' => $this->getStandardTable()->fetchAll(),
            'sms' => $this->getSmsTable()->fetchAll(),
            'name' => $user->name,
        ));
    }

   // module/Album/src/Album/Controller/AlbumController.php:
    public function getAlbumTable() {
        if (!$this->smsTable) {
            $sm = $this->getServiceLocator();
            $this->smsTable = $sm->get('Sms\Model\SmsTable');
        }
        return $this->smsTable;
    }

    public function getStandardTable() {
        if (!$this->standardTable) {
            $sm = $this->getServiceLocator();
            $this->standardTable = $sm->get('Standard\Model\StandardTable');
        }
        return $this->standardTable;
    }

    public function getSmsTable() {
        if (!$this->smsTable) {
            $sm = $this->getServiceLocator();
            $this->smsTable = $sm->get('Sms\Model\SmsTable');
        }
        return $this->smsTable;
    }

    public function myaccountAction() {
        $form = new MemberForm();
        $form->get('submit')->setValue('Login');
        $usertable = $this->getUserTable();
        $this->layout()->heading = 'My Account';
        $user_container = new Container('user');
       
        $user = $usertable->getUser($user_container->id);
        return new ViewModel(array(
            'member' => $user,
            'form' => $form
        ));
    }

    public function getUserTable() {
        if (!$this->loginTable) {
            $sm = $this->getServiceLocator();
            $this->loginTable = $sm->get('Login\Model\LoginTable');
        }
        return $this->loginTable;
    }

}
