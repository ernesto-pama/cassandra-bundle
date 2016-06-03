<?php

namespace CassandraBundle\Cassandra\ORM;

use CassandraBundle\Cassandra\Connection;

class SchemaManager
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function _exec($cql)
    {
        $statement = $this->connection->prepare($cql);
        $this->connection->execute($statement);
    }

    public function createTable($name, $fields, $primaryKeyFields = [])
    {
        $fieldsWithType = array_map(function ($field) { return $field['columnName'].' '.$field['type']; });
        $primaryKeyCQL = '';
        if (count($primaryKeyFields) > 0) {
            $primaryKeyCQL = sprintf(',PRIMARY KEY (%s)', implode(',', $primaryKeyFields));
        }

        $this->_exec(sprintf('CREATE TABLE %s (%s%s);', $name, implode(',', $fieldsWithType), $primaryKeyCQL));
    }

    public function dropTable($name)
    {
        $this->_exec(sprintf('DROP TABLE IF EXISTS %s', $name));
    }
}