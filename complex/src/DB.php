<?php

namespace App;

class DB {

    private \PDO $connection;

    public function __construct(string $dsn, string $user, string $password)
    {
        try {
            $this->connection = new \PDO($dsn, $user, $password);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $exception) {
            http_response_code(500);
            die('DB connection failed');
        }
    }

    public function query(string $query, array $bindings = []): bool|\PDOStatement
    {
        $query = $this->connection->prepare($query);
        if (!empty($bindings)) {
            $query->execute($bindings);
        }
        
        return $query;
    }

    public function lastId(): bool|string
    {
        return $this->connection->lastInsertId();
    }
}