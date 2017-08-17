<?php

namespace RequestHandler\Modules\Database;

interface IDatabase
{

    /**
     *
     * Fetch single record from database
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query
     * @return array|null
     */
    public function fetch(string $query, array $bindings = []): ?array;

    /**
     *
     * Fetch multiple record from database
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query
     * @return array|null
     */
    public function fetchAll(string $query, array $bindings = []): ?array;

    /**
     *
     * Save record to database and retrieve its id
     * (INSERT, UPDATE queries)
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query execution
     * @return int
     */
    public function store(string $query, array $bindings): int;

    /**
     *
     * Execute query and return number of affected rows
     *
     * Meant to be used with delete queries
     *
     * @param string $query
     * @param array $bindings
     * @return int
     */
    public function delete(string $query, array $bindings): int;
}