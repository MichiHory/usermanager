<?php

namespace app\Core;

use PDO;

abstract class BaseModel
{
    protected ?PDO $database = null;
}