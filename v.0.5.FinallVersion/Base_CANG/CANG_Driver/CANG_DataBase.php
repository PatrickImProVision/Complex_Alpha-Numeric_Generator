<?php
declare(strict_types=1);

final class CANG_DataBase
{
    private \PDO $PDO;

    /**
     * @param array<int, mixed> $Options
     */
    public function __construct(string $DSN, string $User = '', string $Password = '', array $Options = [])
    {
        $Default_Options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->PDO = new \PDO($DSN, $User, $Password, $Options + $Default_Options);
    }

    /**
     * @param array<string, mixed> $Data
     */
    public function Insert(string $Table, array $Data): int
    {
        if ($Data === []) {
            throw new \InvalidArgumentException('ERR_INSERT_DATA_REQUIRED');
        }

        $Columns = array_keys($Data);
        $Placeholders = array_map(static fn(string $Column): string => ':' . $Column, $Columns);

        $SQL = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $Table,
            implode(', ', $Columns),
            implode(', ', $Placeholders)
        );

        $Statement = $this->PDO->prepare($SQL);
        foreach ($Data as $Column => $Value) {
            $Statement->bindValue(':' . $Column, $Value);
        }
        $Statement->execute();

        return (int) $this->PDO->lastInsertId();
    }

    /**
     * @param array<string, mixed> $Where
     * @return array<int, array<string, mixed>>
     */
    public function Select(string $Table, array $Where = [], string $Columns = '*', int $Limit = 0): array
    {
        $SQL = sprintf('SELECT %s FROM %s', $Columns, $Table);
        $Params = [];

        if ($Where !== []) {
            [$Where_SQL, $Params] = $this->BuildWhere($Where);
            $SQL .= ' WHERE ' . $Where_SQL;
        }

        if ($Limit > 0) {
            $SQL .= ' LIMIT ' . $Limit;
        }

        $Statement = $this->PDO->prepare($SQL);
        foreach ($Params as $Key => $Value) {
            $Statement->bindValue($Key, $Value);
        }
        $Statement->execute();

        /** @var array<int, array<string, mixed>> $Rows */
        $Rows = $Statement->fetchAll();
        return $Rows;
    }

    /**
     * @param array<string, mixed> $Data
     * @param array<string, mixed> $Where
     */
    public function Update(string $Table, array $Data, array $Where): int
    {
        if ($Data === []) {
            throw new \InvalidArgumentException('ERR_UPDATE_DATA_REQUIRED');
        }
        if ($Where === []) {
            throw new \InvalidArgumentException('ERR_WHERE_REQUIRED');
        }

        $Set_Parts = [];
        $Params = [];
        foreach ($Data as $Column => $Value) {
            $Param_Key = ':set_' . $Column;
            $Set_Parts[] = $Column . ' = ' . $Param_Key;
            $Params[$Param_Key] = $Value;
        }

        [$Where_SQL, $Where_Params] = $this->BuildWhere($Where);
        $Params += $Where_Params;

        $SQL = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $Table,
            implode(', ', $Set_Parts),
            $Where_SQL
        );

        $Statement = $this->PDO->prepare($SQL);
        foreach ($Params as $Key => $Value) {
            $Statement->bindValue($Key, $Value);
        }
        $Statement->execute();

        return $Statement->rowCount();
    }

    /**
     * @param array<string, mixed> $Where
     */
    public function Delete(string $Table, array $Where): int
    {
        if ($Where === []) {
            throw new \InvalidArgumentException('ERR_WHERE_REQUIRED');
        }

        [$Where_SQL, $Params] = $this->BuildWhere($Where);
        $SQL = sprintf('DELETE FROM %s WHERE %s', $Table, $Where_SQL);

        $Statement = $this->PDO->prepare($SQL);
        foreach ($Params as $Key => $Value) {
            $Statement->bindValue($Key, $Value);
        }
        $Statement->execute();

        return $Statement->rowCount();
    }

    /**
     * @param array<string, mixed> $Where
     * @return array{0:string,1:array<string,mixed>}
     */
    private function BuildWhere(array $Where): array
    {
        $Parts = [];
        $Params = [];

        foreach ($Where as $Column => $Value) {
            $Param_Key = ':where_' . $Column;
            $Parts[] = $Column . ' = ' . $Param_Key;
            $Params[$Param_Key] = $Value;
        }

        return [implode(' AND ', $Parts), $Params];
    }
}

/*
Simple ISUD Example (Insert, Select, Update, Delete):

// 1) Create DB instance (SQLite example).
$DB = new CANG_DataBase('sqlite:' . __DIR__ . '/Example.sqlite');

// 2) Create a sample table (one-time setup).
$PDO = new PDO('sqlite:' . __DIR__ . '/Example.sqlite');
$PDO->exec('CREATE TABLE IF NOT EXISTS cang_codes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL,
    type_id INTEGER NOT NULL
)');

// 3) Insert
$New_Id = $DB->Insert('cang_codes', [
    'code' => 'AB12CD34',
    'type_id' => 7,
]);

// 4) Select
$Rows = $DB->Select('cang_codes', ['id' => $New_Id]);

// 5) Update
$Changed = $DB->Update('cang_codes', ['code' => 'ZX98YU76'], ['id' => $New_Id]);

// 6) Delete
$Removed = $DB->Delete('cang_codes', ['id' => $New_Id]);
*/
