<?php

namespace SyncBanner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client;

class SyncController extends AbstractActionController {

    protected $term = null;
    protected $uvClient = null;
    protected $maxStudentUnenrollments = null;
    protected $maxTeacherUnenrollments = null;

    public function __construct(){
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if ((error_reporting() & $errno) && ($errno == E_WARNING || $errno == E_USER_WARNING)) {
                echo "PHP Warning in $errfile on line $errline\n";
                echo "$errstr\n";
                exit(1);
            }

            // Fall through to the standard PHP error handler.
            return false;
        }, E_WARNING | E_USER_WARNING);
    }

    public function syncstudentsAction(){

        $logger = $this->myLogger();

        $sl = $this->getServiceLocator();
        $syncStudents = $sl->get('syncStudents');

        $stats = $syncStudents->checkByTerm($this->getTerm());
        $logger->info('syncstudents - check : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes found.');
        $stats = $syncStudents->apply($logger);
        $logger->info('syncstudents - apply : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes done.');

    }

    public function synccoursesAction(){

        $logger = $this->myLogger();

        $sl = $this->getServiceLocator();
        $syncCourses = $sl->get('syncCourses');

        $stats = $syncCourses->checkByTerm($this->getTerm());
        $logger->info('synccourses - check : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes found.');
        $stats = $syncCourses->apply($logger);
        $logger->info('synccourses - apply : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes done.');

    }

    public function syncteachersAction(){

        $logger = $this->myLogger();

        $sl = $this->getServiceLocator();
        $syncTeachers = $sl->get('syncTeachers');

        $stats = $syncTeachers->checkByTerm($this->getTerm());
        $logger->info('syncteachers - check : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes found.');
        $stats = $syncTeachers->apply($logger);
        $logger->info('syncteachers - apply : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes done.');
    }

    public function syncenrollmentsAction(){
        $request = $this->getRequest();
        $maxUnenrollments = $request->getParam('max_unenrollments');

        if (isset($maxUnenrollments) && !ctype_digit($maxUnenrollments)) {
            echo "Invalid parameter\n";
            return;
        }

        $logger = $this->myLogger();

        $sl = $this->getServiceLocator();
        $syncEnrollments = $sl->get('syncEnrollments');

        $term = $this->getTerm();
        if (!isset($maxUnenrollments)) {
            $maxUnenrollments = $this->getMaxStudentUnenrollments();
        }

        $stats = $syncEnrollments->checkByTerm($term, $maxUnenrollments);

        // Live insurrance against mass unenrollment from Banner...
        if ($stats['delete'] > $maxUnenrollments) {
            $logger->info('syncenrollments - check : too many student unenrollments ('.$stats['delete'].'), aborted');
            printf("Too many student unenrollments (%d): aborted\n", $stats['delete']);
            return;
        }

        $logger->info('syncenrollments - check : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes found.');
        $stats = $syncEnrollments->apply($logger);
        $logger->info('syncenrolllments - apply : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes done.');

    }

    public function syncchargesAction(){
        $request = $this->getRequest();
        $maxUnenrollments = $request->getParam('max_unenrollments');

        if (isset($maxUnenrollments) && !ctype_digit($maxUnenrollments)) {
            echo "Invalid parameter\n";
            return;
        }

        $logger = $this->myLogger();

        $sl = $this->getServiceLocator();
        $syncCharges = $sl->get('syncCharges');

        $term = $this->getTerm();
        if (!isset($maxUnenrollments)) {
            $maxUnenrollments = $this->getMaxTeacherUnenrollments();
        }

        $stats = $syncCharges->checkByTerm($term, $maxUnenrollments);

        // Live insurrance against mass unenrollment from Banner...
        if ($stats['delete'] > $maxUnenrollments) {
            $logger->info('synccharges - check : too many teacher unenrollments ('.$stats['delete'].'), aborted');
            printf("Too many teacher unenrollments (%d): aborted\n", $stats['delete']);
            return;
        }

        $logger->info('synccharges - check : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes found.');
        $stats = $syncCharges->apply($logger);
        $logger->info('synccharges - apply : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes done.');

    }


    public function syncnontitulairesAction(){
        echo "This code has never been reviewed and should not be trusted.\n";
        return;

        $logger = $this->myLogger();

        $sl = $this->getServiceLocator();
        $syncservice = $sl->get('syncNontitulaires');

        //sync tables 'ntcharges' and 'nontitulaires'
        $stats = $syncservice->check($this->getTerm());
        $logger->info('syncnontitulaires - check : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes found.');
        $stats = $syncservice->apply();
        $logger->info('syncnontitulaires - apply : '.$stats['insert'].' inserts, '.$stats['update'].' updates, '.$stats['delete'].' deletes done.');

    }

    public function syncstudentAction() {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $term = $request->getParam('term');

        if (empty($term)) {
            $term = $this->getTerm();
        }

        if (strlen($term) != 6 || !ctype_digit($term)) {
            echo "The term $term is invalid\n";
            return;
        }

        $sl = $this->getServiceLocator();
        $mapper = $sl->get('Admin\Mapper\Banner\BannerStudentMapper');
        $xmlString = $sl->get('SyncBanner\Service\XmlStringService');

        $uvClient = $this->getUvClient();

        if (ctype_digit($id)) {
            $students = $mapper->getStudentInscriptionByBannerId($id, $term)->toArray();
        } else {
            $students = $mapper->getStudentInscriptionByNetId($id, $term)->toArray();
        }

        if (empty($students)) {
            echo "No student with ID '$id' found\n";
            return;
        }

        $student = array_change_key_case($students[0], CASE_LOWER);
        $student['term'] = $term;

        printf("Name: %s %s\n", $student['firstname'], $student['lastname']);
        printf("Email: %s\n\n", $student['email']);

        printf("NetID: %s\n", $student['netid']);
        printf("BannerID: %s\n\n", $student['bannerid']);

        printf("Cohorts: %s\n", $student['cohort']);
        printf("Faculties: %s\n\n", $student['faculty']);

        if (empty($student['email'])) {
            $student['email'] = 'null@uv.ulb.ac.be';
        }

        if (empty($student['firstname'])) {
            $student['firstname'] = '(vide)';
        }

        printf("Synchronize user: ");

        $response = $uvClient->syncStudent($student);
        if ($response === 'Success') {
            printf("Success\n");
        } else {
            printf("Failure\n");
        }

        $bannerId = $student['bannerid'];
        $enrollments = $mapper->getStudentEnrollments($bannerId, $term)->toArray();

        if (empty($enrollments)) {
            echo "No enrollment found\n";
        }

        $i = 1;
        foreach ($enrollments as $enrollment) {
            $enrollment = array_change_key_case($enrollment);
            printf("Enrollment %2d: %s-%s (%s.%s): ", $i, $enrollment['subjcode'], $enrollment['crsenumb'], $enrollment['nre'], $enrollment['term']);

            $enrollment['bannerid'] = $bannerId;

            $response = $uvClient->syncStudentEnrollment($enrollment);
            if ($response === 'Success') {
                printf("Success\n");
            } else {
                printf("Failure\n");
            }

            sleep(0.1);
            $i = $i + 1;
        }
    }

    public function getstudentAction() {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $term = $request->getParam('term');

        if (empty($term)) {
            $term = $this->getTerm();
        }

        if (strlen($term) != 6 || !ctype_digit($term)) {
            echo "The term $term is invalid\n";
            return;
        }

        $sl = $this->getServiceLocator();
        $mapper = $sl->get('Admin\Mapper\Banner\BannerStudentMapper');

        if (ctype_digit($id)) {
            $students = $mapper->getStudentByBannerId($id)->toArray();
        } else {
            $students = $mapper->getStudentByNetId($id)->toArray();
        }

        if (empty($students)) {
            echo "No student with ID '$id' found\n";
            return;
        }

        $student = array_change_key_case($students[0], CASE_LOWER);

        $firstname = trim($student['firstname']);
        if (empty($firstname)) {
            $firstname = '(null)';
        }

        $lastname  = trim($student['lastname']);
        if (empty($lastname)) {
            $lastname = '(null)';
        }

        printf("Name: %s %s %s\n", $student['title'], $firstname, $lastname);
        printf("Email: %s\n", $student['email']);
        printf("Initials: %s\n", $student['initials']);
        printf("Sex: %s\n\n", $student['sex']);

        printf("NetID: %s\n", $student['netid']);
        printf("BannerID: %s\n", $student['bannerid']);
        printf("Matricule: %s\n\n", $student['matricule']);

        $enrollments = $mapper->getStudentEnrollments($student['bannerid'], $term)->toArray();

        if (empty($enrollments)) {
            echo "No enrollment found\n";
        }

        $i = 1;
        foreach ($enrollments as $enrollment) {
            $enrollment = array_change_key_case($enrollment);
            printf("Enrollment %2d: %s-%s (%s.%s)\n", $i, $enrollment['subjcode'], $enrollment['crsenumb'], $enrollment['nre'], $enrollment['term']);
            $i = $i + 1;
        }
    }

    public function getcourseAction() {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $term = $request->getParam('term');

        if (empty($term)) {
            $term = $this->getTerm();
        }

        if (strlen($term) != 6 || !ctype_digit($term)) {
            echo "The term $term is invalid\n";
            return;
        }

        $sl = $this->getServiceLocator();
        $mapper = $sl->get('Admin\Mapper\Banner\BannerCourseMapper');

        if (ctype_digit($id)) {
            $mnemonic = $mapper->getMnemonicForNre($id, $term)->toArray();
            if (empty($mnemonic)) {
                printf("No course with ID %s found in the term %s\n", $id, $this->term);
                return;
            }
            $mnemonic = array_change_key_case($mnemonic[0], CASE_LOWER);
            $mnemonic = $mnemonic['mnemonic'];
        } else {
            $parts = explode('-', $id);
            if (count($parts) == 2) {
                $mnemonic = $parts[0] . '-' . substr($parts[1], 0, 1) . '-' . substr($parts[1], 1);
            } else {
                $mnemonic = $id;
            }
        }

        $courses = $mapper->getCourseByMnemonicAndTerm($mnemonic, $term)->toArray();

        if (empty($courses)) {
            printf("No course with ID %s found in the term %s\n", $id, $this->term);
            return;
        }

        $course_nres = array();

        $course_nre = array_change_key_case($courses[0], CASE_LOWER);
        $course_nre['fullname'] = array($course_nre['fullname']);

        $courses = array_slice($courses, 1);

        foreach ($courses as $course) {
            $course = array_change_key_case($course, CASE_LOWER);

            if ($course['nre'] != $course_nre['nre']) {
                $course_nres[] = $course_nre;

                $course_nre = $course;
                $course_nre['fullname'] = array($course['fullname']);

                $nre = $course['nre'];
            } else {
                array_push($course_nre['fullname'], $course['fullname']);
            }
        }

        $course_nres[] = $course_nre;

        printf("Mnemonic: %s\n", $course_nre['mnemonique']);
        printf("Subject code: %s\n", $course_nre['subjcode']);
        printf("Faculty: %s\n", $course_nre['faculty']);
        printf("Course number: %s\n", $course_nre['crsenumb']);
        printf("Title: %s\n", $course_nre['title']);
        printf("Term: %s\n\n", $course_nre['term']);

        foreach ($course_nres as $course_nre) {
            printf("NRE: %s\n", $course_nre['nre']);
            printf("Quadrimester: %s\n", $course_nre['quadri']);
            printf("Teacher(s): %s\n", join(', ', $course_nre['fullname']));
            printf("Activities: %s\n\n", $course_nre['activities']);
        }

        $enrollments = $mapper->getCourseTeachers($mnemonic, $term)->toArray();

        if (empty($enrollments)) {
            printf("No teacher found\n\n");
        } else {
            $count = count($enrollments);
            printf("%d teacher enrollment%s:\n", $count, ($count > 1) ? 's' : '');
            foreach ($enrollments as $enrollment) {
                $enrollment = array_change_key_case($enrollment);
                printf("    %s %s (%s)\n", $enrollment['firstname'], $enrollment['lastname'], $enrollment['bannerid']);
            }
            printf("\n");
        }

        $enrollments = $mapper->getCourseStudents($mnemonic, $term)->toArray();
        if (empty($enrollments)) {
            printf("No student found\n\n");
        } else {
            $count = count($enrollments);
            printf("%d student enrollment%s:\n", $count, ($count > 1) ? 's' : '');
            foreach ($enrollments as $enrollment) {
                $enrollment = array_change_key_case($enrollment);
                printf("    %s %s (%s)\n", $enrollment['firstname'], $enrollment['lastname'], $enrollment['bannerid']);
            }
        }
    }

    public function synccourseAction() {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $term = $request->getParam('term');

        if (empty($term)) {
            $term = $this->getTerm();
        }

        if (strlen($term) != 6 || !ctype_digit($term)) {
            echo "The term $term is invalid\n";
            return;
        }

        $uvClient = $this->getUvClient();

        $sl = $this->getServiceLocator();
        $mapper = $sl->get('Admin\Mapper\Banner\BannerCourseMapper');

        if (ctype_digit($id)) {
            $mnemonic = $mapper->getMnemonicForNre($id, $term)->toArray();
            if (empty($mnemonic)) {
                printf("No course with ID %s found in the term %s\n", $id, $this->term);
                return;
            }
            $mnemonic = array_change_key_case($mnemonic[0], CASE_LOWER);
            $mnemonic = $mnemonic['mnemonic'];
        } else {
            $parts = explode('-', $id);
            if (count($parts) == 2) {
                $mnemonic = $parts[0] . '-' . substr($parts[1], 0, 1) . '-' . substr($parts[1], 1);
            } else {
                $mnemonic = $id;
            }
        }

        $courses = $mapper->getCourseByMnemonicAndTerm($mnemonic, $term)->toArray();

        if (empty($courses)) {
            printf("No course with ID %s found in the term %s\n", $id, $this->term);
            return;
        }

        $course = array_change_key_case($courses[0], CASE_LOWER);

        printf("Mnemonic: %s\n", $course['mnemonique']);
        printf("Subject code: %s\n", $course['subjcode']);
        printf("Faculty: %s\n", $course['faculty']);
        printf("Course number: %s\n", $course['crsenumb']);
        printf("Title: %s\n", $course['title']);
        printf("Term: %s\n\n", $course['term']);

        printf("Synchronize course: ");

        $response = $uvClient->syncCourse($course);
        if ($response === 'Success') {
            printf("Success\n");
        } else {
            printf("Failure\n");
        }

        $enrollments = $mapper->getCourseTeachers($mnemonic, $term)->toArray();

        if (empty($enrollments)) {
            echo "No teacher found\n";
        }

        foreach ($enrollments as $enrollment) {
            $enrollment = array_change_key_case($enrollment);
            printf("Enroll teacher %s in course %s-%s: ", $enrollment['bannerid'], $enrollment['subjcode'], $enrollment['crsenumb']);

            $response = $uvClient->syncTeacherEnrollment($enrollment);
            if ($response === 'Success') {
                printf("Success\n");
            } else {
                printf("Failure\n");
            }

            sleep(0.1);
        }

        $enrollments = $mapper->getCourseStudents($mnemonic, $term)->toArray();
	    $enrollments = ''; //Comment this line to disable students SNYC.

        if (empty($enrollments)) {
            echo "No student found\n";
        }
	else{
        foreach ($enrollments as $enrollment) {
            $enrollment = array_change_key_case($enrollment);
            printf("Enroll student %s in course %s-%s: ", $enrollment['bannerid'], $enrollment['subjcode'], $enrollment['crsenumb']);

            $response = $uvClient->syncStudentEnrollment($enrollment);
            if ($response === 'Success') {
                printf("Success\n");
            } else {
                printf("Failure\n");
            }

            sleep(0.1);
        }
	}

    }

    public function unenrollAction() {
        $request = $this->getRequest();
        $bannerid = $request->getParam('bannerid');
        $course = $request->getParam('course');

        $parts = explode('-', $course);
        if (count($parts) != 3 || strlen($parts[2]) != 6 || !ctype_digit($parts[2])) {
            echo "The course $course is invalid\n";
            return;
        }

        $uvClient = $this->getUvClient();

        $data = array();
        $data['bannerid'] = $bannerid;
        $data['subjcode'] = $parts[0];
        $data['crsenumb'] = $parts[1];
        $data['term'] = $parts[2];

        $response = $uvClient->syncUnenrollment($data);
        if ($response === 'Success') {
            printf("Success\n");
        } else {
            printf("Failure\n");
        }
    }

    public function cleanupAction() {
        $request = $this->getRequest();
        $type = $request->getParam('type');

        $sl = $this->getServiceLocator();
        $charge = $sl->get('Admin\Mapper\ChargeMapper');
        $course = $sl->get('Admin\Mapper\CourseMapper');
        $enrollment = $sl->get('Admin\Mapper\EnrollmentMapper');
        $student = $sl->get('Admin\Mapper\StudentMapper');
        $teacher = $sl->get('Admin\Mapper\TeacherMapper');

        switch ($type) {
            case 'log':
                echo "To be implemented\n";
                break;
            case 'table':
                $charge->clearTable();
                $course->clearTable();
                $enrollment->clearTable();
                $student->clearTable();
                $teacher->clearTable();
                break;
            case 'temporary':
                $charge->dropTemporaryTable();
                $course->dropTemporaryTable();
                $enrollment->dropTemporaryTable();
                $student->dropTemporaryTable();
                $teacher->dropTemporaryTable();
                break;
            case 'update':
                $charge->clearUpdatesTable();
                $course->clearUpdatesTable();
                $enrollment->clearUpdatesTable();
                $student->clearUpdatesTable();
                $teacher->clearUpdatesTable();
                break;
            default:
                echo "Invalid argument $type\n";
        }
    }

    public function testAction() {        
    }

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

    private function getTerm() {
        if ($this->term === null) {
            $sl = $this->getServiceLocator();
            $config = $sl->get('Config');
            $this->term = $config['sync']['term'];
        }

        return $this->term;
    }

    private function getMaxStudentUnenrollments() {
        if ($this->maxStudentUnenrollments === null) {
            $sl = $this->getServiceLocator();
            $config = $sl->get('Config');
            $this->maxStudentUnenrollments = (int) $config['sync']['max_student_unenrollments'];
        }

        return $this->maxStudentUnenrollments;
    }

    private function getMaxTeacherUnenrollments() {
        if ($this->maxTeacherUnenrollments === null) {
            $sl = $this->getServiceLocator();
            $config = $sl->get('Config');
            $this->maxTeacherUnenrollments = (int) $config['sync']['max_teacher_unenrollments'];
        }

        return $this->maxTeacherUnenrollments;
    }

    private function getLock() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            throw new Exception('can\'t create socket: '. socket_last_error($socket));
        }
        
        // Hide warning. Return true if the socket can be bound. Otherwise, it means
        // another instance of the script is running.
        return @socket_bind($socket, '127.0.0.1', 10011);
    }

}

?>
