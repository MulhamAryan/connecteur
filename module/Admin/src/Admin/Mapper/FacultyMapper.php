<?php

namespace Admin\Mapper;

use Admin\Model\FacultyModel;
use Admin\Mapper\AbstractStandardMapper;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\AbstractResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Ddl\CreateTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class FacultyMapper extends AbstractStandardMapper {

    protected $dbAdapter;
    protected $hydrator;
    protected $model;

    protected $tablename = 'faculties';

    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, FacultyModel $facultyModel){

        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->model = $facultyModel;
    }

    public function getByCode($code){
        $faculty_codes = array (
            'B' => 'Lettres, Traduction et Communication',
            'C' => 'Droit et Criminologie',
            'D' => 'Philosophie et Sciences sociales',
            'E' => 'Sciences psychologiques et éducation',
            'F' => 'Sciences',
            'G' => 'Médecine',
            'H' => 'Ecole polytechnique de Bruxelles',
            'I' => 'Sciences de la Motricité',
            'J' => 'Pharmacie',
            'L' => 'Ecole de Santé Publique',
            'O' => 'Institut d\'études européennes',
            'P' => 'Architecture',
            'S' => 'Solvay Brussels School of Economics and Management'
        );

        $code = strtoupper($code);
        if (array_key_exists($code, $faculty_codes)) {
            $faculty = $faculty_codes[$code];
        } else {
            $faculty = 'Non catégorisé';
        }

        return $faculty;
    }
}


?>
