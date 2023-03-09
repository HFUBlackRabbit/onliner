<?php

namespace App\Models;

use App\DB;

abstract class AbstractModel
{

    protected static string $table;

    public function __construct()
    {
    }

    protected static function tableName(): string
    {
        return static::$table ?? strtolower(str_replace([__NAMESPACE__, 'Model', '\\'], '', static::class)) . 's';
    }

    /**
     * @param array $conditions
     * @param int|null $limit
     * @return self[]
     */
    public static function find(array $conditions, ?int $limit): array
    {
        $query = 'SELECT * FROM `' . self::tableName() . '`';

        if (!empty($conditions)) {
            $query .= ' WHERE';
            $data = [];
            foreach ($conditions as $field => $value) {
                $operand = '=';

                if (in_array($value, [null], true)) {
                    $operand = 'IS';
                }
                $data[] = ' `' . $field . '` ' . $operand. ' :' . $field;
            }
            $query .= implode(' AND ', $data);
        }

        if (!empty($limit)) {
            $query .= ' LIMIT ' . $limit;
        }

        $query = App()->db->query($query, $conditions);

        return $query->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function findOne(array $conditions): ?static
    {
        $result = self::find($conditions, 1);
        return array_shift($result);
    }

    public static function findOneById(int $id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function create(array $data): ?static
    {

        try {
            $record = App()->db->query(
                'INSERT INTO `' . self::tableName() . '` (`' . implode('`, `', array_keys($data)) . '`) VALUES (:' . (implode(
                    ', :',
                    array_keys($data)
                )) . ')',
                $data
            );
        } catch (\Exception $exception) {
            return null;
        }

        $id = App()->db->lastId();

        if ($id == null) {
            throw new \Exception('Record dont created');
        }

        return self::findOneById($id);
    }

    public function update(array $values): void
    {
        $query = 'UPDATE ' . self::tableName() . ' SET ';
        $data = [];
        foreach ($values as $field => $value) {
            $data[] = '`' . $field . '` = :' . $field;
        }
        $query .= implode(', ', $data);

        $query .= ' WHERE `id` = ' . $this->id;

        var_dump($query);
        App()->db->query($query, $values);
    }
}