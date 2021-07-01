<?php

namespace SyncBanner\Controller;

use Admin\Service\SyncStudentsService;
use SyncBanner\Service\XmlStringService;
use Zend\Http\Client;
use SyncBanner\Entity\XmlString;
use Zend\Mvc\Controller\AbstractActionController;

class SyncuvController extends AbstractActionController {
    protected $uvClient = null;

    private $sleep = 0.01;

    public function getUvClient() {
        // TODO: code duplicate with SyncController::getUvClient !!!

        if ($this->uvClient === null) {
            $sl = $this->getServiceLocator();
            $config = $sl->get('Config');

            $host = $config['uv']['host'];
            $token = $config['uv']['token'];
            $term = $config['sync']['term'];

            $client = $sl->get('SyncBanner\Service\XmlStringService');
            $client->init($host, $token, $term);

            $this->uvClient = $client;
        }

        return $this->uvClient;
    }

    public function sendstudentsupdatesAction(){

        $sl = $this->getServiceLocator();
        $syncStudents = $sl->get('syncStudents');

        $client = $this->getUvClient();
        $logger = $this->myLogger();

        // TODO: list only action 1 and action 2 instead of pruning the list afterwards.
        $updates = $syncStudents->listupdates();

        foreach($updates as $update){
            $action = $update['action'];
            if ($action == 1 || $action == 2){
                if (empty($update['netid'])) {
                    printf("Action %d: missing NetID for student %s %s (%s)\n", $action, $update['firstname'], $update['lastname'], $update['bannerid']);
                    continue;
                }

                if (empty($update['email'])) {
                    $update['email'] = 'null@uv.ulb.ac.be';
                }

                if (empty($update['firstname'])) {
                    $update['firstname'] = '(vide)';
                }

                if (empty($update['cohort'])) {
                    $update['cohort'] = '';
                }

                if (empty($update['faculty'])) {
                    $update['faculty'] = '';
                }

                if (empty($update['status'])) {
                    $update['status'] = '';
                }

                $response = $client->syncStudent($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('syncstudents - send : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('syncstudents - send : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname'] . ' : ' . $errorMessage);
                    printf("Failed to send student %s %s (%s - %s): %s\n", $update['firstname'], $update['lastname'], $update['bannerid'], $update['netid'], $errorMessage);
                }
            }

            sleep($this->sleep);
        }
    }

    public function sendteachersupdatesAction(){

        $sl = $this->getServiceLocator();
        $syncTeachers = $sl->get('syncTeachers');

        $client = $this->getUvClient();
        $logger = $this->myLogger();

        // TODO: list only action 1 and action 2 instead of pruning the list afterwards.
        $updates = $syncTeachers->listupdates();

        foreach($updates as $update){
            $action = $update['action'];
            if ($action == 1 || $action == 2){
                if (empty($update['netid'])) {
                    printf("Action %d: missing NetID for teacher %s %s (%s)\n", $action, $update['firstname'], $update['lastname'], $update['bannerid']);
                    continue;
                }

                if (empty($update['email'])) {
                    $update['email'] = 'null@uv.ulb.ac.be';
                }

                if (empty($update['firstname'])) {
                    $update['firstname'] = '(vide)';
                }

                $response = $client->syncTeacher($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('syncteachers - send : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('syncteachers - send : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname'] . ' : ' . $errorMessage);
                    printf("Failed to send teacher %s %s (%s - %s): %s\n", $update['firstname'], $update['lastname'], $update['bannerid'], $update['netid'], $errorMessage);
                }
            }

            sleep($this->sleep);
        }
    }

    public function sendcoursesupdatesAction(){

        $sl = $this->getServiceLocator();
        $syncCourses = $sl->get('syncCourses');

        $client = $this->getUvClient();
        $logger = $this->myLogger();

        $updates = $syncCourses->listupdates();

        foreach($updates as $update){
            $action = $update['action'];
            if ($action == 1 || $action == 2){
                $response = $client->syncCourse($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('synccourses - send : ' . $update['subjcode'] . ' - ' . $update['crsenumb'] . ' - ' . $update['title'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('synccourses - send : ' . $update['subjcode'] . ' - ' . $update['crsenumb'] . ' - ' . $update['title'] . ' : ' . $errorMessage);
                    printf("Failed to send course %s - %s - %s: %s\n", $update['subjcode'], $update['crsenumb'], $update['title'], $errorMessage);
                }
            } else if ($action == 3) {
                // No course deletion so far. The LMB plugin does not apparently handle that and it is too dangerous to delete a course without confirmation.
            }

            sleep($this->sleep);
        }
    }


    public function sendenrollmentsupdatesAction(){

        $sl = $this->getServiceLocator();
        $syncEnrollments = $sl->get('syncEnrollments');

        $client = $this->getUvClient();
        $logger = $this->myLogger();

        $updates = $syncEnrollments->listupdates();

        foreach($updates as $update){
            $action = $update['action'];
            if ($action == 1 || $action == 2){
                $response = $client->syncStudentEnrollment($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('syncenrollments - send : enroll student ' . $update['bannerid'] . ' in course ' . $update['nre'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('syncenrollments - send : enroll student '
                        . $update['bannerid'] . ' in course ' . $update['nre'] . ' : ' . $errorMessage);
                    printf("Failed to enroll student %s in course %s: %s\n", $update['bannerid'], $update['nre'], $errorMessage);
                }
            }else if($action == 3){
                $response = $client->syncUnenrollment($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('syncenrollments - send : unenroll student '
                        . $update['bannerid'] . ' from course ' . $update['nre'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('syncenrollments - send : unenroll student '
                        . $update['bannerid'] . ' from course ' . $update['nre'] . ' : ' . $errorMessage);
                    printf("Failed to unenroll student %s from course %s: %s\n", $update['bannerid'], $update['nre'], $errorMessage);
                }
            }

            sleep($this->sleep);
        }
    }

    public function sendchargesupdatesAction(){

        $sl = $this->getServiceLocator();
        $syncCharges = $sl->get('syncCharges');

        $client = $this->getUvClient();
        $logger = $this->myLogger();

        $updates = $syncCharges->listupdates();

        foreach($updates as $update){
            $action = $update['action'];
            if ($action == 1 || $action == 2){
                $response = $client->syncTeacherEnrollment($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('synccharges - send : enroll teacher ' . $update['bannerid'] . ' in course ' . $update['subjcode'] . '-' . $update['crsenumb'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('synccharges - send : enroll teacher '
                        . $update['bannerid'] . ' in course ' . $update['subjcode'] . '-' . $update['crsenumb'] . ' : ' . $errorMessage);
                    printf("Failed to enroll teacher %s in course %s-%s: %s\n", $update['bannerid'], $update['subjcode'], $update['crsenumb'], $errorMessage);
                }
            }else if($action == 3){
                $response = $client->syncUnenrollment($update->getArrayCopy());
                if ($response === 'Success') {
                    $logger->info('synccharges - send : unenroll teacher '
                        . $update['bannerid'] . ' from course ' . $update['subjcode'] . '-' . $update['crsenumb'] . ' : success');
                } else {
                    $errorMessage = isset($response['faultString']) ? $response['faultString'] : 'failure';
                    $logger->info('synccharges - send : unenroll teacher '
                        . $update['bannerid'] . ' from course ' . $update['subjcode'] . '-' . $update['crsenumb'] . ' : ' . $errorMessage);
                    printf("Failed to unenroll teacher %s from course %s-%s: %s\n", $update['bannerid'], $update['subjcode'], $update['crsenumb'], $errorMessage);
                }
            }

            sleep($this->sleep);
        }
    }
}

