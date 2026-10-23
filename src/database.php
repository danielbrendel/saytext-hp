<?php

require_once(__DIR__ . '/config.php');

function getParamType($param)
{
    switch (gettype($param)) {
        case 'boolean':
            return \PDO::PARAM_BOOL;
            break;
        case 'integer':
            return \PDO::PARAM_INT;
            break;
        case 'NULL':
            return \PDO::PARAM_NULL;
            break;
        case 'string':
            return \PDO::PARAM_STR;
            break;
        case 'double':
            return \PDO::PARAM_STR;
            break;
    }

    return \PDO::PARAM_STR;
}

function dbquery($pdo, $qry, $opt = null)
{
    $prp = $pdo->prepare($qry);

    if ($opt !== null) {
        foreach ($opt as $key => $item) {
            $prp->bindValue($key + 1, $item, getParamType($item));
        }
    }

    $prp->execute();

    $error = $pdo->errorInfo();
    if ($error[0] !== '00000') {
        throw new \Exception('SQL error: ' . $error[0] . ':' . $error[1] . ' -> ' . $error[2]);
    }

    $result = $prp->fetchAll();

    return $result;
}
