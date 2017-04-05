<?php

namespace core\harry;

use core\collection\Collection;
use core\exceptions\PotterException\PotterException;

class Potter
{
    /**
     * @var object
     */
    private $db;

    /**
     * @var array
     *
     * Все ячейки таблицы
     */
    protected $rows = [];

    /**
     * @var array
     *
     * Все свойства которых нету (__set) | Названия ячеек в таблице
     */
    protected $undefinedProperty = [];

    /**
     * @var array
     *
     * Data of post after insert/update
     */
    public $dataAfterLastSave = [];

    /**
     * @var string
     *
     * The model name in database
     */
    protected $table = '';

    /**
     * @var array
     *
     * Весь sql запрос
     */
    private $bindings
        = [
            'select' => [],
            'join' => [],
            'where' => [],
            'having' => [],
            'order' => [],
        ];

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var array
     */
    protected $joins;

    /**
     * @var array
     */
    protected $wheres = [];

    /**
     * @var array
     */
    protected $havings;

    /**
     * @var array
     */
    protected $orders;

    /**
     * @var int
     */
    protected $limit;

    /**
     * Potter constructor.
     *
     * @param string $setTable используется для колбека при джоине
     */
    public function __construct(string $setTable = '')
    {
        $this->rows = collect();
        $this->undefinedProperty = collect();

        $this->tableNameToProperty();

        $this->db = is_null($this->db)
            ? ConnectDatabase::instance()
            : $this->db;

        $this->rowsToProperty();
    }

    /**
     * @param array $columns
     * @return Potter
     */
    public function select($columns = ['*']): Potter
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * @param string $table
     * @param $column
     * @param string $equally
     * @param string $entityColumn
     * @return Potter
     */
    public function leftJoin(string $table, $column, string $equally = '=', string $entityColumn = ''): Potter
    {
        if ($column instanceof \Closure) {
            $this->closureJoin($table, $column);
        } else {
            $this->joins .= "LEFT JOIN $table ON $column = $entityColumn ";
        }

        return $this;
    }

    /**
     * @param string $table
     * @param $column
     * @param string $entityColumn
     * @return Potter
     */
    public function rightJoin(string $table, $column, string $entityColumn): Potter
    {
        $this->joins .= "RIGHT JOIN $table ON $column = $entityColumn ";

        return $this;
    }

    /**
     * @param string $table
     * @param $column
     * @param string $entityColumn
     * @return Potter
     */
    public function innerJoin(string $table, $column, string $entityColumn): Potter
    {
        $this->joins .= "INNER JOIN $table ON $column = $entityColumn ";

        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param $value
     */
    public function where(string $column, string $operator = '=', $value): void
    {
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'and' => empty($this->wheres) ? false : true,
        ];
    }

    /**
     * @param int $id
     * @return mixed
     * @throws PotterException
     */
    public function findId(int $id)
    {
        if (0 == $id) {
            throw new PotterException('id cant be 0');
        }

        $this->select();
        $this->where('id', '=', $id);
        $this->limit(1);

        $data = $this->get();

        if (!$data) {
            throw new PotterException('Incorrect id for findId');
        }

        // Для update
        if (empty($this->dataAfterLastSave)) {
            $this->dataAfterLastSave = $data->data;
        }

        return $data->data;
    }

    /**
     * @param int $key
     */
    public function limit(int $key): void
    {
        $this->limit = $key;
    }

    /**
     * Более одной записи
     * Если $data пустая, то метод вызывается не в данном классе,
     * используется методом all в этом классе
     *
     * @param array|string $data
     *
     * @return array
     */
    public function get($data = '')
    {
        $sql = null;

        if (0 != strlen($data)) {
            $sql = $data;
        } else {
            $sql = $this->toSql();
        }

        // Выполняем запрос + подставляем данные
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->wheres);
        $this->wheres = [];

        $array = $this->getDataObjects($stmt);

        return $array;
    }

    /**
     * @return bool|mixed
     */
    public function save()
    {
        // Если обновляем пост...
        if (!empty($this->dataAfterLastSave)) {
            return $this->update();
        }

        $keys = $this->getKeysForSave();
        $values = $this->getValuesForSave();

        $result = $this->insert($keys, $values);

        if (!$result) {
            return false;
        }

        // Добавляем данные последний записи в $dataAfterLastSave
        $this->dataAfterSave();

        return true;
    }

    /**
     * @param string $keys
     * @param string $values
     * @return mixed
     */
    public function insert(string $keys, string $values)
    {
        $sql = 'INSERT INTO '.$this->table.'('.$keys.') VALUES ('.$values.')';
        // Выполняем запрос + подставляем данные
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute($this->undefinedProperty);

        return $result;
    }

    /**
     * @return bool
     * @throws PotterException
     */
    public function update(): bool
    {
        if (empty($this->undefinedProperty)) {
            throw new PotterException('Incorrect property for update');
        }

        $values = $this->getValuesForUpdate();

        $sql = 'UPDATE '.$this->table.' SET '.$values.' WHERE id = '.$this->id;

        // Выполняем запрос + подставляем данные
        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute($this->undefinedProperty);

        if (!$result) {
            return false;
        }

        // Добавляем данные последний записи в $dataAfterLastSave
        $this->dataAfterSave($this->id);

        return true;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $sql = 'DELETE FROM '.$this->table.' WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([$id]);

        return $result;
    }

    /**
     * Возвращает все записи
     *
     * @return array
     */
    public function all(): array
    {
        return $this->get('SELECT * FROM '.$this->table);
    }

    /**
     * @return string
     */
    private function toSql(): string
    {
        // Работа с ячейками для выборки
        $this->columnsForSql();

        // Работа с джоинами
        $this->joinsForSql();

        // Работа с условиями
        $this->wheresForSql();

        $sql = $this->getSql();

        return $sql;
    }

    /**
     * Метод перебирает все указанные ячейки для выборки (если они указанны) и пихает их в $bindings['select']
     * Если не указаны - * (все)
     */
    private function columnsForSql(): void
    {
        $columns = '';

        if (is_null($this->columns) || '*' == $this->columns[0]) {
            $columns = '*';
        } else {
            $columns = implode($this->columns, implode([',']));
        }

        $this->bindings['select'] = $columns;
    }

    /**
     * Метод перебирает все джоины и пихает их в $bindings['joins']
     */
    private function joinsForSql(): void
    {
        if (!empty($this->joins)) {
            $this->bindings['join'] = $this->joins;
        }
    }

    /**
     * Метод перебирает все условия и пихает их в $bindings['where']
     * А значения пихает в $wheres
     */
    private function wheresForSql(): void
    {
        $sql = '';
        $key = [];

        foreach ($this->wheres as $k) {
            // используется для PDO execute
            // Если (example) user.id , то будет userid
            $bindColumn = $this->changeColumnsForBindings($k['column']);

            // если уже есть where, то пишем and
            $sql .= true == $k['and'] ? ' and ' : 'WHERE ';

            // example: user.id = :userid
            $sql .= $k['column'].' '.$k['operator'].$bindColumn;

            // Закидываем данные которые есть в условие
            $key[] = $k['value'];
        }

        // Присваиваем данные, чтобы использовать потом для запроса
        $this->wheres = $key;

        if (!empty($this->wheres)) {
            $this->bindings['where'] = $sql;
        }
    }

    /**
     * @param $table
     * @param $column
     */
    private function closureJoin($table, $column)
    {
        // Если есть переименнование (as), то обрезаем и берем название таблицы (оригинальное)
        if (stristr($table, 'as')) {
            $table = trim(explode('as', $table)[0]);
        }

        $join = new JoinClause($table);

        call_user_func($column, $join);

        // джоины closure
        $this->joins .= $join->joins;

        $array = [];

        foreach ($join->wheres as $k) {
            // Добавляем название таблицы которая была присоединена (user_id = image.user_id)
            $k['column'] = $join->table.'.'.$k['column'];
            $array[] = $k;
        }

        // Условия closure
        $this->wheres = $array;
    }

    /**
     * @param $data
     *
     * @return array
     *
     * Функция используется при каждой выборке.
     * Каждой записе присваивается объект класса HarryPotter
     */
    private function getDataObjects($data)
    {
        $array = [];

        // Присваиваем каждому материалу объект класса HarryPotter
        if (1 == $this->limit) {
            $array = $data->fetchObject('\\core\\harry\\HarryPotter');
        } else {
            while ($row = $data->fetchObject('\\core\\harry\\HarryPotter')) {
                $array[] = $row;
            }
        }

        return $array;
    }

    /**
     * @param $key
     *
     * @return string
     *
     * Превращает вид user.id в userid
     */
    private function changeColumnsForBindings($key)
    {
        return stristr($key, '.')
            ? ', '.implode(explode('.', $key))
            : ':'.$key;
    }

    /**
     * @param int $id
     */
    private function dataAfterSave(int $id = 0): void
    {
        if (0 == $id) {
            $id = (int) $this->db->lastInsertId();
        }

        $this->dataAfterLastSave = $this->findId($id);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (!is_null($this->dataAfterLastSave) && $this->dataAfterLastSave->has($key)) {
            return $this->dataAfterLastSave->get($key);
        }

        return false;
    }

    /**
     * set all rows of current table
     */
    private function rowsToProperty(): void
    {
        // Берем все поля из таблицы
        $stmt = $this->db->query("SHOW FIELDS FROM $this->table")->fetchAll();

        // Записываем каждое поле(ячейку) в $rows
        foreach ($stmt as $k => $v) {
            $this->rows->set(
                $v['Field'],
                [
                    'type' => $v['Type'],
                    'default' => $v['Default'],
                ]
            );
        }
    }

    /**
     * set table name to $table
     */
    private function tableNameToProperty(): void
    {
        // если в модели (из которой идет вызов) нету свойства $table и записывает в $table название таблицы на
        // основе 'modelName' + 's'
        if (empty($this->table) && 0 == strlen($setTable)) {
            $this->table = strtolower(end(explode('\\', get_called_class())).'s');
        } elseif (1 < strlen($setTable)) {
            $this->table = $setTable;
        }
    }


    /**
     * Метод записывает все необъявленные свойства в $undefinedProperty (ячейки таблицы)
     *
     * @param $name
     * @param $data
     *
     * @throws PotterException
     */
    public function __set($name, $data)
    {
        if ($this->rows->has($name)) {
            $this->undefinedProperty->set($name, $data);
        } else {
            throw new PotterException("Incorrect field ( $name ) name");
        }
    }

    /**
     * @return string
     */
    private function getKeysForSave(): string
    {
        $keys = '';

        foreach ($this->undefinedProperty->all() as $k => $v) {
            // Если больше одной записи - ставим запятую
            $keys .= 0 != strlen($keys)
                ? ", $k"
                : $k;
        }

        return $keys;
    }

    /**
     * @return string
     */
    private function getValuesForSave(): string
    {
        $values = '';

        foreach ($this->undefinedProperty->all() as $k => $v) {
            // Если больше одной записи - ставим запятую
            $values .= 0 != strlen($values)
                ? ', '.$this->changeColumnsForBindings($k)
                : $this->changeColumnsForBindings($k);

            // Значения для PDO execute
            $array[] = $v;
            $this->undefinedProperty = $array;
        }

        return $values;
    }

    /**
     * @return string
     */
    private function getValuesForUpdate(): string
    {
        $values = '';

        foreach ($this->undefinedProperty->all() as $k => $v) {
            $values .= 0 == strlen($values)
                ? $k.' = '.$this->changeColumnsForBindings($k)
                : ', '.$k.' = '.$this->changeColumnsForBindings($k);

            $array[] = $v;
            $this->undefinedProperty = $array;
        }

        return $values;
    }

    /**
     * @return string
     */
    private function getSql(): string
    {
        $sql = '';

        if (!empty($this->bindings['select'])) {
            $sql .= 'SELECT '.$this->bindings['select'].' FROM '.$this->table.' ';
        }

        if (!empty($this->bindings['join'])) {
            $sql .= $this->bindings['join'];
        }

        if (!empty($this->bindings['where'])) {
            $sql .= $this->bindings['where'];
        }

        if (!empty($this->limit)) {
            $sql .= ' LIMIT '.$this->limit;
        }

        return $sql;
    }
}