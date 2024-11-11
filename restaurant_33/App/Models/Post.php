<?php

namespace App\Models;

use PDO;

class Post extends BaseModel
{
    protected $table = 'posts';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    // Add any post-specific methods here
}
