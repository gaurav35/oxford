<?php

namespace Standard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;
use Standard\Model\Standard;          // <-- Add this import
use Standard\Form\StandardForm;       // <-- Add this import

class StandardController extends AbstractActionController {

    protected $standardTable;

    public function indexAction() {
        $this->layout()->heading = 'All Classes';

        //var_dump($this->getStandardTable()->fetchAll());die;
        $paginator = $this->getStandardTable()->fetchAll(true);
        
     // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
     // set the number of items per page to 10
       $paginator->setItemCountPerPage(4);

      return new ViewModel(array(
            'paginator' => $paginator
        ));

    }

    public function addAction() {
        try {
            $this->layout()->heading = 'Add Class';
            $form = new StandardForm();
            $form->get('submit')->setValue('Submit');
            $request = $this->getRequest();

            if ($request->isPost()) {
                $standard = new Standard();
                $form->setInputFilter($standard->getInputFilter());
                $form->setData($request->getPost());
                //var_dump($form->getData());die;
                if ($form->isValid()) {
                    $standard->exchangeArray($form->getData());
                    $this->getStandardTable()->saveStandard($standard);
                    // Redirect to list of standards
                    $this->flashMessenger()->setNamespace('success')->addMessage('Class inserted.');
                    return $this->redirect()->toRoute('standard');
                }
            }
            return array('form' => $form);
        } catch (Exception $e) {
            $this->flashMessenger()->setNamespace('default')->addMessage($e->getMessages());
        }
    }

    public function editAction() {
        try{
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('standard', array(
                        'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $standard = $this->getStandardTable()->getStandard($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('standard', array(
                        'action' => 'index'
            ));
        }

        $form = new StandardForm();
        $form->bind($standard);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($standard->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getStandardTable()->saveStandard($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('standard');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );            
        }catch(Exception $e){
            $this->flashMessenger()->setNamespace('default')->addMessage($e->getMessages());
        }
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('standard');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getStandardTable()->deleteStandard($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('standard');
        }

        return array(
            'id' => $id,
            'standard' => $this->getStandardTable()->getStandard($id)
        );
    }

    public function getStandardTable() {
        if (!$this->standardTable) {
            $sm = $this->getServiceLocator();
            $this->standardTable = $sm->get('Standard\Model\StandardTable');
        }
        return $this->standardTable;
    }

}
