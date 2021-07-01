<?php
namespace Admin\Service;

use Admin\Mapper\CourseMapper;
use Admin\Mapper\Banner\BannerCourseMapper;
use Admin\Controller\Plugin\MyLogger;

class SyncCoursesService {

    protected $courseMapper;
    protected $bannerCourseMapper;

    public function __construct(CourseMapper $courseMapper, BannerCourseMapper $bannerCourseMapper){

        $this->courseMapper = $courseMapper;
        $this->bannerCourseMapper = $bannerCourseMapper;

    }

    public function checkByTerm($term){
        ini_set('memory_limit', '512M');

        // Get all courses in the current term.
        $results = $this->bannerCourseMapper->getCoursesByTerm_Array($term);
        $stats = array('insert' => 0,'update' => 0,'delete' => 0,'total' => 0);

        // Store them in a temporary table. If previous instance ran fine, the table should not exist.
        $this->courseMapper->createTemporaryTable();
        $stats['total'] = $this->courseMapper->loadTemporaryTable_Array($results);

        // Do not process empty result set, otherwise, table comparison will remove all entries.
        if ($stats['total'] == 0) {
            $this->courseMapper->clearUpdatesTable();
            return $stats;
        }

        // Compare the temporary table with the current one to know what changed since the last checkpoint.
        $results = $this->courseMapper->compareTablesWhere();

        // Store the result of the comparison in the update table:
        //     - Action is 1 for inserting a new entry.
        //     - Action is 2 for updating an existing entry.
        //     - Action is 3 for deleting an existing entry.
        //
        //     - Origine is 1 if the entry comes from the current table.
        //     - Origine is 2 if the entry comes from the temporary table.
        $this->courseMapper->clearUpdatesTable();
        $this->courseMapper->loadUpdatesTable_Array($results);

        // Read again the entries from the update table (why?).
        $updates = $this->courseMapper->getAllUpdates_Array();

        // Post-process the updates.
        foreach ($updates as $update){
            $res = $this->courseMapper->getUpdatesWhere_Array(array('subjcode = ?' => $update['subjcode'], 'crsenumb = ?' => $update['crsenumb']));
            if (($res->count()==2) && ($update['origine'] == 2)){
                // Entry was already present but has changed. Find the original entry, copy back its id in the new one,
                // tag it for update and invalidate the original one.
                $tmp = $this->courseMapper->getUpdateWhere_Array(array('origine = 1', 'subjcode = ?' => $update['subjcode'], 'crsenumb = ?' => $update['crsenumb']));
                $update['origid'] = $tmp['origid'];
                $update['action'] = 2;
                $tmp['action'] = 0;
                $this->courseMapper->saveUpdate_Array($update->getArrayCopy());
                $this->courseMapper->saveUpdate_Array($tmp);
                $stats['update']++;
            }elseif ($res->count()==1){
                if ($update['origine'] == 1){
                    // Origine is the current table. The entry does not exist anymore. This means that the course has been deleted.
                    $stats['delete']++;
                }
                else {
                    // Origine is the temporary table. It's a new entry.
                    $stats['insert']++;
                }
            }
        }
        return($stats);
    }

    public function listupdates(){

        $updates = $this->courseMapper->getAllUpdates_Array();
        return $updates;
    }

    public function apply(MyLogger $logger){

        $stats = array('insert' => 0,'update' => 0,'delete' => 0, 'total' => 0);

        $updates = $this->courseMapper->getAllUpdates_Array();
        foreach ($updates as $update){

            $stats['total']++;
            $action = $update['action'];
            $origid = $update['origid'];
            unset($update['action']);
            unset($update['origine']);
            unset($update['origid']);

            if ($action == 1){
                // Insert entry in the current table.
                $logger->info('synccourses - insert : ' . $update['subjcode'] . ' - ' . $update['crsenumb'] . ' - ' . $update['faculty'] . ' - ' . $update['title']);
                unset($update['id']);
                $this->courseMapper->save_Array($update->getArrayCopy());
                $stats['insert']++;
            }elseif ($action == 2){
                // Update entry in the current table.
                $logger->info('syncstudents - update : ' . $update['subjcode'] . ' - ' . $update['crsenumb'] . ' - ' . $update['faculty'] . ' - ' . $update['title']);
                $update['id'] = $origid;
                $this->courseMapper->save_Array($update->getArrayCopy());
                $stats['update']++;
            }elseif ($action == 3){
                // Delete entry. Skip deletion so far.
                $logger->info('synccourses - delete : ' . $update['subjcode'] . ' - ' . $update['crsenumb'] . ' - ' . $update['faculty'] . ' - ' . $update['title']);
                $stats['delete']++;
            }
        }

        $this->courseMapper->dropTemporaryTable();
        return $stats;

    }
}
