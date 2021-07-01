<?php
namespace Admin\Service;

use Admin\Mapper\TeacherMapper;
use Admin\Mapper\Banner\BannerTeacherMapper;
use Admin\Controller\Plugin\MyLogger;

class SyncTeachersService {

    protected $teacherMapper;
    protected $bannerTeacherMapper;

    public function __construct(TeacherMapper $teacherMapper, BannerTeacherMapper $bannerTeacherMapper){

        $this->teacherMapper = $teacherMapper;
        $this->bannerTeacherMapper = $bannerTeacherMapper;

    }


    public function checkByTerm($term){
        ini_set('memory_limit', '512M');

        // Get all teachers by term.
        $results = $this->bannerTeacherMapper->getTeachersByTerm_Array($term);
        $stats = array('insert' => 0,'update' => 0,'delete' => 0, 'total' => 0);

        // Store them in a temporary table. If previous instance ran fine, the table should not exist.
        $this->teacherMapper->createTemporaryTable();
        $stats['total'] = $this->teacherMapper->loadTemporaryTable_Array($results);

        // Do not process empty result set, otherwise, table comparison will remove all entries.
        if ($stats['total'] == 0) {
            $this->teacherMapper->clearUpdatesTable();
            return $stats;
        }

        // Compare the temporary table with the current one to know what changed since the last checkpoint.
        $results = $this->teacherMapper->compareTablesWhere();

        // Store the result of the comparison in the update table:
        //     - Action is 1 for inserting a new entry.
        //     - Action is 2 for updating an existing entry.
        //     - Action is 3 for deleting an existing entry.
        //
        //     - Origine is 1 if the entry comes from the current table.
        //     - Origine is 2 if the entry comes from the temporary table.
        $this->teacherMapper->clearUpdatesTable();
        $this->teacherMapper->loadUpdatesTable_Array($results);

        // Read again the entries from the update table (why?).
        $updates = $this->teacherMapper->getAllUpdates_Array();

        // Post-process the updates.
        foreach ($updates as $update){
            $res = $this->teacherMapper->getUpdatesWhere_Array(array('bannerid = ?' => $update['bannerid']));
            if (($res->count()==2) && ($update['origine'] == 2)){
                // Entry was already present but has changed. Find the original entry, copy back its id in the new one,
                // tag it for update and invalidate the original one.
                $tmp = $this->teacherMapper->getUpdateWhere_Array(array('origine = 1', 'bannerid = ?' => $update['bannerid']));
                $update['origid'] = $tmp['origid'];
                $update['action'] = 2;
                $tmp['action'] = 0;
                $this->teacherMapper->saveUpdate_Array($update->getArrayCopy());
                $this->teacherMapper->saveUpdate_Array($tmp);
                $stats['update']++;
            }elseif ($res->count()==1){
                // New entry or deleted entry.
                if ($update['origine'] == 1){
                    // Origine is the current table. The entry does not exist anymore.
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

        $updates = $this->teacherMapper->getAllUpdates_Array();
        return $updates;
    }


    public function apply(MyLogger $logger){

        $stats = array('insert' => 0,'update' => 0,'delete' => 0, 'total' => 0);

        $updates = $this->teacherMapper->getAllUpdates_Array();
        foreach ($updates as $update){
            $stats['total']++;
            $action = $update['action'];
            $origid = $update['origid'];
            unset($update['action']);
            unset($update['origine']);
            unset($update['origid']);

            if ($action == 1){
                // Insert entry in the current table.
                $logger->info('syncteachers - insert : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname']);
                unset($update['id']);
                $this->teacherMapper->save_Array($update->getArrayCopy());
                $stats['insert']++;
            }elseif ($action == 2){
                // Update entry in the current table.
                $logger->info('syncteachers - update : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname']);
                $update['id'] = $origid;
                $this->teacherMapper->save_Array($update->getArrayCopy());
                $stats['update']++;
            }elseif ($action == 3){
                // Delete entry. Skip deletion so far.
                $logger->info('syncteachers - delete : ' . $update['bannerid'] . ' - ' . $update['netid'] . ' - ' . $update['firstname'] . ' ' . $update['lastname']);
                $stats['delete']++;
            }
            //TODO set done to 1 : de cette maniere on peut consulte les updates effectues mais plus les faire une seconde fois
        }

        $this->teacherMapper->dropTemporaryTable();
        return $stats;

    }
}
