<?php
namespace Admin\Service;

use Admin\Mapper\ChargeMapper;
use Admin\Mapper\Banner\BannerChargeMapper;
use Admin\Controller\Plugin\MyLogger;

class SyncChargesService {

    protected $chargeMapper;
    protected $bannerChargeMapper;

    public function __construct(ChargeMapper $chargeMapper, BannerChargeMapper $bannerChargeMapper){

        $this->chargeMapper = $chargeMapper;
        $this->bannerChargeMapper = $bannerChargeMapper;

    }

    public function checkByTerm($term, $maxUnenrollments){
        ini_set('memory_limit', '512M');

        // Get all teacher enrollments in the current term.
        $results = $this->bannerChargeMapper->getChargesByTerm_Array($term);
        $stats = array('insert' => 0,'update' => 0,'delete' => 0,'total' => 0);

        // Store them in a temporary table. If previous instance ran fine, the table should not exist.
        $this->chargeMapper->createTemporaryTable();
        $stats['total'] = $this->chargeMapper->loadTemporaryTable_Array($results);

        // Do not process empty result set, otherwise, table comparison will remove all entries.
        if ($stats['total'] == 0) {
            $this->chargeMapper->clearUpdatesTable();
            return $stats;
        }

        // Compare the temporary table with the current one to know what changed since the last checkpoint.
        $results = $this->chargeMapper->compareTablesWhere();

        // Store the result of the comparison in the update table:
        //     - Action is 1 for inserting a new entry.
        //     - Action is 2 for updating an existing entry.
        //     - Action is 3 for deleting an existing entry.
        //
        //     - Origine is 1 if the entry comes from the current table.
        //     - Origine is 2 if the entry comes from the temporary table.
        $this->chargeMapper->clearUpdatesTable();
        $this->chargeMapper->loadUpdatesTable_Array($results);

        // Read again the entries from the update table (why?).
        $updates = $this->chargeMapper->getAllUpdates_Array();

        // Post-process the updates.
        foreach ($updates as $update){
            $res = $this->chargeMapper->getUpdatesWhere_Array(array('bannerid = ?' => $update['bannerid'], 'subjcode = ?' => $update['subjcode'], 'crsenumb = ?' => $update['crsenumb']));
            if (($res->count()==2) && ($update['origine'] == 2)){
                // Entry was already present but has changed. Find the original entry, copy back its id in the new one,
                // tag it for update and invalidate the original one.
                //
                // It is really possible to have updates in this case? I don't think so...
                $tmp = $this->chargeMapper->getUpdateWhere_Array(array('bannerid = ?' => $update['bannerid'], 'subjcode = ?' => $update['subjcode'], 'crsenumb = ?' => $update['crsenumb'], 'origine = 1'));
                $update['origid'] = $tmp['origid'];
                $update['action'] = 2;
                $tmp['action'] = 0;
                $this->chargeMapper->saveUpdate_Array($update->getArrayCopy());
                $this->chargeMapper->saveUpdate_Array($tmp);
                $stats['update']++;
            }elseif ($res->count()==1){
                if ($update['origine'] == 1){
                    // Origine is the current table. The entry does not exist anymore. This means that the student has been
                    // unenrolled from the course.
                    $stats['delete']++;
                }
                else {
                    // Origine is the temporary table. It's a new entry.
                    $stats['insert']++;
                }
            }
        }

        // Live insurrance against mass unenrollment from Banner...
        if ($stats['delete'] > $maxUnenrollments) {
            $this->chargeMapper->clearUpdatesTable();
            $this->chargeMapper->dropTemporaryTable();
        }

        return($stats);
    }


    public function listupdates(){

        $updates = $this->chargeMapper->getAllUpdates_Array();
        return $updates;
    }


    public function apply(MyLogger $logger){

        $stats = array('insert' => 0,'update' => 0,'delete' => 0, 'total' => 0);

        $updates = $this->chargeMapper->getAllUpdates_Array();
        foreach ($updates as $update){

            $stats['total']++;
            $action = $update['action'];
            $origid = $update['origid'];
            unset($update['action']);
            unset($update['origine']);
            unset($update['origid']);

            if ($action == 1){
                // Insert entry in the current table.
                $logger->info('synccharges - insert : ' . $update['bannerid'] . ' - ' . $update['subjcode'] . ' - ' . $update['crsenumb']);
                unset($update['id']);
                $this->chargeMapper->save_Array($update->getArrayCopy());
                $stats['insert']++;
            }elseif ($action == 2){
                // Update entry in the current table. Is it really possible to have updates in this case? I don't think so.
                $logger->info('synccharges - update : ' . $update['bannerid'] . ' - ' . $update['subjcode'] . ' - ' . $update['crsenumb']);
                $update['id'] = $origid;
                $this->chargeMapper->save_Array($update->getArrayCopy());
                $stats['update']++;
            }elseif ($action == 3){
                // Delete entry. Skip deletion so far.
                $logger->info('synccharges - delete : ' . $update['bannerid'] . ' - ' . $update['subjcode'] . ' - ' . $update['crsenumb']);
                $this->chargeMapper->delete($origid);
                $stats['delete']++;
            }
            //TODO set done to 1 : de cette maniere on peut consulte les updates effectues mais plus les faire une seconde fois
        }

        $this->chargeMapper->dropTemporaryTable();
        return $stats;

    }
}
