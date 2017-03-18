<?php

namespace core\harry;

class JoinClause extends Potter
{
    /**
     * JoinClause constructor.
     *
     * @param string $table
     */
    public function __construct(string $table)
    {
        parent::__construct($table);
    }

    /**
     * @param        $column
     * @param string $equally
     * @param string $entityColumn
     *
     * @return $this
     */
    public function on($column, $equally = '=', string $entityColumn = '')
    {
        // Берем название таблицы по джоину
        $key = explode('.', $column)[0];

        // Если названия совпадают - все хорошо, если нет - берем оригинальное
        // название таблицы и новое (tables as table)
        $table = $this->table == $key ?: $this->table.' '.$key;

        $this->leftJoin($table, $column, $equally, $entityColumn);

        return $this;
    }
}