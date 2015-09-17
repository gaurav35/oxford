<?php

namespace Student\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Json\Json;
use Zend\View\Model\ViewModel;
use Student\Model\Student;         // <-- Add this import
use Student\Model\Studentupload;
use Standard\Model\Standard;        // <-- Add this import
use Student\Form\StudentForm;       // <-- Add this import
use Student\Form\StudentFormXls;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;

include './Spreadsheet/Excel/reader.php';

class StudentController extends AbstractActionController {

    protected $studentTable;
    protected $standardTable;

    public function indexAction() {
        $this->layout()->heading = 'Student List';
        // var_dump($this->getStudentTable()->getStudentWithClass());die;
        // grab the paginator from the AlbumTable
        $paginator = $this->getStudentTable()->getStudentWithClass(true);
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
            $this->layout()->heading = 'Add Student';
            $dbAdapter = $this->getStandardTable();
            $form = new StudentForm($dbAdapter);
            $form->get('submit')->setValue('Submit');
            $request = $this->getRequest();


            if ($request->isPost()) {
                $student = new Student();
                $form->setInputFilter($student->getInputFilter());
                $form->setData($request->getPost());
                if ($form->isValid()) {

                    $result = $this->getStudentTable()->checkStudent($request->getPost('class'), $request->getPost('student_name'), $request->getPost('roll_no'), $request->getPost('admission_no'));

                    if (empty($result)) {

                        $student->exchangeArray($form->getData());
                        $this->getStudentTable()->saveStudent($student);
                        // Redirect to list of students
                        $this->flashMessenger()->setNamespace('success')->addMessage('Record has been submitted.');
                        return $this->redirect()->toRoute('student');
                    }
                }
            }
            return array('form' => $form);
        } catch (Exception $e) {
            $this->flashMessenger()->setNamespace('default')->addMessage($e->getMessages());
        }
    }

    public function editAction() {
        try {
            $this->layout()->heading = 'Edit Student';
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('student', array(
                            'action' => 'add'
                ));
            }

            // Get the Album with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $student = $this->getStudentTable()->getStudent($id);
            } catch (\Exception $ex) {
                return $this->redirect()->toRoute('student', array(
                            'action' => 'index'
                ));
            }
            $dbAdapter = $this->getStandardTable();
            $form = new StudentForm($dbAdapter);
            $form->bind($student);
            $form->get('submit')->setAttribute('value', 'Edit');
            $form->get('admission_no')->setAttribute('readonly', 'readonly');
            $form->get('roll_no')->setAttribute('readonly', 'readonly');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setInputFilter($student->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $this->getStudentTable()->saveStudent($form->getData());
                    // Redirect to list of albums
                    $this->flashMessenger()->setNamespace('success')->addMessage('Record has been Updated.');
                    return $this->redirect()->toRoute('student');
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
            );
        } catch (Exception $e) {
            $this->flashMessenger()->setNamespace('default')->addMessage($e->getMessages());
        }
    }

    public function deleteAction() {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('student');
            }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $del = $request->getPost('del', 'No');

                if ($del == 'Yes') {
                    $id = (int) $request->getPost('id');
                    $this->getStudentTable()->deleteStudent($id);
                }

                // Redirect to list of albums
                $this->flashMessenger()->setNamespace('success')->addMessage('Record has been deleted.');
                return $this->redirect()->toRoute('student');
            }

            return array(
                'id' => $id,
                'student' => $this->getStudentTable()->getStudent($id)
            );
        } catch (Exception $e) {
            $this->flashMessenger()->setNamespace('default')->addMessage($e->getMessages());
        }
    }

    public function getStudentTable() {
        if (!$this->studentTable) {
            $sm = $this->getServiceLocator();
            $this->studentTable = $sm->get('Student\Model\StudentTable');
        }
        return $this->studentTable;
    }

    public function getStandardTable() {
        if (!$this->standardTable) {
            $sm = $this->getServiceLocator();
            $this->standardTable = $sm->get('Standard\Model\StandardTable');
        }
        return $this->standardTable;
    }

    public function studentClassWiseAction() {

        $this->layout()->heading = 'Student Class-Wise Detail';
        $dbAdapter = $this->getStandardTable();
        $form = new StudentForm($dbAdapter);
        $request = $this->getRequest();


        if ($request->isPost()) {

            $class_id = (int) $request->getPost('class');

            if ($class_id) {
                $form->setData($request->getPost());
                return new ViewModel(array(
                    'student' => $this->getStudentTable()->getStudentByClass($class_id), 'form' => $form
                ));
            } else {
                return new ViewModel(array(
                    'student' => $this->getStudentTable()->getStudentByClass(0), 'form' => $form
                ));
            }
        }

        return new ViewModel(array(
            'student' => $this->getStudentTable()->getStudentByClass(0), 'form' => $form
        ));
    }

    public function studentClassWiseAjaxAction() {

        if ($_GET['class']) {

            $class_id = (int) $_GET['class'];

            if ($class_id) {
                return $this->getResponse()->setContent(Json::encode($this->getStudentTable()->getStudentByClass($class_id)));
            } else {
                return $this->getResponse()->setContent(Json::encode($this->getStudentTable()->getStudentByClass(0)));
            }
        }

        return $this->getResponse()->setContent(Json::encode($this->getStudentTable()->getStudentByClass(0)));
    }

    public function studentxlsAction() {
        $this->layout()->heading = "Add Student Excel";
        $dbAdapter = $this->getStandardTable();
        $form = new StudentFormXls($dbAdapter);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $uploadfile = new Studentupload();
            $form->setInputFilter($uploadfile->getInputFilter());
            $nonFile = $request->getPost()->toArray();
            $File = $this->params()->fromFiles('fileupload');
            $data = array_merge(
                    $nonFile, array('fileupload' => $File['name'])
            );

            $form->setData($data);

            if ($form->isValid()) {
                $size = new Size(array('min' => 2000000)); //minimum bytes filesize
                $ext = new Extension(array('case' => true, 'extension' => array('xls', 'xlsx')));
                $adapter = new \Zend\File\Transfer\Adapter\Http();
                $adapter->setValidators(array($size), $File['name']);
                $adapter->setValidators(array($ext), $File['name']);
                if (!$adapter->isValid()) {
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }
                    $form->setMessages(array('fileupload' => $error));
                } else {
                    $data = new \Spreadsheet_Excel_Reader();
                    $data->setOutputEncoding('CP1251');

                    $objReader = $data->read($File['tmp_name']);

                    $excelcolumn = array('STUDENT NAME', 'FATHERS NAME', 'MOTHERS NAME', 'MOBILE1', 'MOBILE2', 'ROLL NO/SL NO', 'ADMISSION NO');

                    for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                        if ($data->sheets[0]['cells'][1][$j] !== $excelcolumn[$j - 1]) {
                            //echo $data->sheets[0]['cells'][1][$j]. "===". $excelcolumn[$j - 1] . "<BR>";
                             return $this->redirect()->toRoute('student', array(
                                        'action' => 'studentxls'
                            ));
                        }
                    }
                    
                    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                            $arraydata = array();
                        for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
                            
                 $arraydata[] = isset($data->sheets[0]['cells'][$i][$j]) ? $data->sheets[0]['cells'][$i][$j]: Null;
                                  }
                            if ( !empty($arraydata) && !is_null($arraydata[0]) ) {
                                $result = $this->getStudentTable()->checkStudent($request->getPost('class'), $arraydata[0], $arraydata[5], $arraydata[6]);
                            if (empty($result)) {
                                $this->getStudentTable()->saveStudentXls($arraydata);
                            }
                            $this->flashMessenger()->setNamespace('success')->addMessage("Csv File has been installed.");
                        }
                            
                        unset($arraydata);
                    }
                }
            }
        }
        return array('form' => $form);
    }

    public function downloadAction() {

        $filepath = dirname(__DIR__) . "/Detail.xls";
        // I download the excel file .......
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($filepath, 'r'));
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-type', 'application/vnd.ms-excel', true)
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . basename($filepath) . '"')
                ->addHeaderLine('Content-Length', filesize($filepath));

        $response->setHeaders($headers);
        return $response;
    }

}
