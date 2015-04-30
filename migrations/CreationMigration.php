<?php
/**
 * @link https://github.com/thinker-g/yii2-helpers
 * @copyright Copyright (c) thinker_g (Jiyan.guo@gmail.com)
 * @license MIT
 * @version v1.0.0
 * @author thinker_g
 */

namespace thinker_g\Helpers\migrations;

use yii\db\Migration;
use yii\base\Exception;

/**
 * Abstract class for creating and dropping tables while installing/removing modules.
 * The migration extends this class must define the attribute $tables.
 * @author thinker-g
 *
 */
abstract class CreationMigration extends Migration
{
    /* Availables "keys" used in table definition keys. */
    const K_COLS = 'COLS';
    const K_PK = 'PK';
    const K_FK = 'FK';
    const K_IDXS = 'IDXS';
    const K_OPT = 'OPT';
    const K_UNQ = 'UNIQUE';

    /**
     * Default table creation options appended to the CREATE statements.
     * This is indexed by database driver name, which can be obtained from Yii::$app->db->driverName.
     * Leave this an empty array or null to appended nothing while creating tables;
     * The OPT setting in each table's definition will overwrite this.]
     *
     * @see http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
     * @var array
     */
    public $defaultTableOptions = [
        'mysql' => 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
    ];

    /**
     * Tables definitions, in which keys are table names.
     * For each table definition the key of its elements can be obtained by constants of this class.
     * @example
     * public $tables = [
     *     'tableName' => [
     *         'COLS' => [
     *             'columnName1' => 'definition1',
     *             'columnName2' => 'definition2',
     *         ],
     *         'PK' => ['col1', 'col2'],
     *         'FK' => [
     *             'fkName1' => ['colName', 'refTable', 'refColName', 'onDeletionAction', 'onUpdateAction']
     *         ],
     *         'IDXS' => [
     *             'idxName' => [
     *                 'COLS' => ['col1', 'col2'],
     *                 'UNIQUE' => false
     *             ]
     *         ],
     *         'OPT' => 'Additional options, will overwrite $defaultTableOptions if there is an available one.'
     *     ], // table 1
     *     // table 2 ...
     * ];
     *
     * @var array
     */
    public $tables = [];

    public function safeUp()
    {
        $defaultTabOpt = null;
        if (isset($this->defaultTableOptions[$this->db->driverName])) {
            $defaultTabOpt = $this->defaultTableOptions[$this->db->driverName];
        }

        foreach ($this->tables as $tableName => $def) {
            if (isset($def[self::K_OPT])) {
                $tabOpt = $def[self::K_OPT];
            } else {
                $tabOpt = $defaultTabOpt;
            }
            $this->createTable($tableName, $def[self::K_COLS], $tabOpt);
            isset($def[self::K_PK]) && $this->addPrimaryKey(null, $tableName, $def[self::K_PK]);
            if (isset($def[self::K_FK])) {
                foreach ($def[self::K_FK] as $fkName => $fkDef) {
                    isset($fkDef[3]) || $fkDef[3] = null;
                    isset($fkDef[4]) || $fkDef[4] = null;
                    $this->addForeignKey(
                        $fkName,
                        $tableName,
                        $fkDef[0],
                        $fkDef[1],
                        $fkDef[2],
                        $fkDef[3],
                        $fkDef[4]
                    );
                }
            } // FK added
            if (isset($def[self::K_IDXS])) {
                foreach ($def[self::K_IDXS] as $idxName => $idxDef) {
                    $this->createIndex(
                        $idxName,
                        $tableName,
                        $idxDef[self::K_COLS],
                        isset($idxDef[self::K_UNQ]) && $idxDef[self::K_UNQ]
                    );
                }
            } // IDX added
        } // one table created

    }

    public function safeDown()
    {
        $tables = array_reverse(array_keys($this->tables));
        foreach ($tables as $table) {
            $this->dropTable($table);
        }
    }

    /**
     * @inheritdoc
     * @see \yii\db\Migration::init()
     */
    public function init()
    {
        parent::init();
        if (empty($this->tables)) {
            throw new Exception('Cannot find table definitions in property "tables".', 400);
        }

    }

}
