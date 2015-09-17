<?php
namespace Sms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;
use Sms\Model\Sms;          // <-- Add this import
use Sms\Form\SmsForm;       // <-- Add this import
use Sms\Form\SendSmsForm;       // <-- Add this import
use Sms\View\Helper\Smshelper as SmsHelper;

class SmsController extends AbstractActionController
{
    protected $smsTable;
    public function indexAction()
    {
        $this->layout()->heading = 'Sms Template';
     //var_dump($this->getStandardTable()->fetchAll());die;
        $paginator = $this->getSmsTable()->fetchAll(true);
        
     // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
     // set the number of items per page to 10
        $paginator->setItemCountPerPage(4);

      return new ViewModel(array(
            'paginator' => $paginator
        ));
        
    }

    public function addAction()
    {
        try{
         $this->layout()->heading = 'Add Sms Template';
         $form = new SmsForm();
         $form->get('submit')->setValue('Submit');
         $request = $this->getRequest();
         
        if ($request->isPost()) {
            $sms = new Sms();            
            $form->setInputFilter($sms->getInputFilter());
            $form->setData($request->getPost());
            //var_dump($form->getData());die;
            if ($form->isValid()) {
                $sms->exchangeArray($form->getData());
                $this->getSmsTable()->saveSms($sms);
                // Redirect to list of smss
                $this->flashMessenger()->setNamespace('success')->addMessage('Template Successfully Inserted.');
                return $this->redirect()->toRoute('sms');
            }
        }
         return array('form' => $form);
        }catch(Exception $e){
            $this->flashMessenger()->setNamespace('default')->addMessage($e->getMessages());
        }
    }

    public function editAction()
    {
        $this->layout()->heading = 'Edit Sms Template';
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('sms', array(
                'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $sms = $this->getSmsTable()->getSms($id);
        }
        catch (Exception $ex) {
            return $this->redirect()->toRoute('sms', array(
                'action' => 'index'
            ));
        }

        $form  = new SmsForm();
        $form->bind($sms);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($sms->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSmsTable()->saveSms($form->getData());
                $this->flashMessenger()->setNamespace('success')->addMessage('Template Successfully Edited.');
                // Redirect to list of albums
                return $this->redirect()->toRoute('sms');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );  
        
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('sms');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getSmsTable()->deleteSms($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('sms');
        }

        return array(
            'id'    => $id,
            'sms' => $this->getSmsTable()->getSms($id)
        );
    }
     
    public function getSmsTable()
    {
        if (!$this->smsTable) {
            $sm = $this->getServiceLocator();
            $this->smsTable = $sm->get('Sms\Model\SmsTable');
        }
        return $this->smsTable;
    }
    
    public function sendsmsAction(){
        $this->layout()->heading = "SEND SMS";
        $sm = $this->getServiceLocator()->get('Student\Model\StudentTable');
        $form = new SendSmsForm($sm);
        $form->get('submit')->setValue('Send');
        $dbAdapter = $this->getServiceLocator()->get('Standard\Model\StandardTable');
        $standard = $dbAdapter->fetchAll();
        
        
         $request = $this->getRequest();
        if ($request->isPost()) {
           $sms_id  = (int) $request->getPost('sms_id');
           $sms = $this->getSmsTable()->getSms($sms_id);
           $template = $sms->sms_temp;
           $selected_mobiles  = $request->getPost('selected_mobiles');
           $classname  = $request->getPost('classname');
           $selected_mobiles = implode(',',$selected_mobiles);
           $selected_mobiles = array_filter( explode(',', $selected_mobiles) );
           
           if(!empty($selected_mobiles)){
               $selected_mobiles = implode(',',$selected_mobiles);
                      $sendbulksms = new SmsHelper();
                      $bulksms = $sendbulksms->sendBulkSms($selected_mobiles,$template);
           }
           
           
        }
            
         return new ViewModel(array(
            'sms' => $this->getSmsTable()->fetchAll(),
            'standard' => $standard,
            'form' => $form
        ));
    }
      public function smsbalanceAction(){
        $this->layout()->heading = "Sms Details";
//        try{
//            $smsbalanceobj = new SmsHelper();
//            $smsbalance =  $smsbalanceobj->smsbalance();  
//            $campaigndetail = $smsbalanceobj->smsgetcampaigndetail();
//            if ($smsbalance->isSuccess()) {
//            // the POST was successful
//                  $smsbalance->getContent();
//            return new ViewModel(array(
//              'response' => $smsbalance->getContent(),
//              'error' => "",
//              ));
//           } 
//       }catch (Exception $e){
//              return new ViewModel(array(
//              'error' => "Message: " . $e->getMessage() . "\n",
//              ));
//        }
     }
}