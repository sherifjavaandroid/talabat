<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DBTrait
{

    public function clearTableRecordsBy($column, $id)
    {
        $tables = $this->getTablesWithColumn($column);
        foreach ($tables as $key => $table) {
            $statement = "delete from $table where $column = $id";
            DB::statement($statement);
        }
    }

    public function clearTableRecords($column, $truncate = true)
    {
        $tables = $this->getTablesWithColumn($column);
        foreach ($tables as $key => $table) {
            if ($truncate) {
                try {
                    DB::table($table)->truncate();
                } catch (\Exception $e) {
                    //delete instead of truncate
                    DB::table($table)->delete();
                }
            } else {
                DB::table($table)->delete();
            }
        }
    }

    public function getTablesWithColumn($column)
    {
        $returnTables = [];
        $tables = $this->getTables();
        foreach ($tables as $key => $table) {
            if (Schema::hasColumn($table, $column)) {
                $returnTables[] = $table;
            }
        }
        return $returnTables;
    }

    function getTables()
    {
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);
        return $tables;
    }


    function removeRecordsFromDB($tables, $column, $value)
    {
        if (is_string($tables)) {
            $tables = [$tables];
        }
        foreach ($tables as $key => $table) {
            $statement = "delete from $table where $column = $value";
            DB::statement($statement);
        }
    }
}
