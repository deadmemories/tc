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
     * @param string $column
     * @param string $equally
     * @param string $entityColumn
     * @return JoinClause
     */
    public function on(string $column, string $equally = '=', string $entityColumn = ''): JoinClause
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