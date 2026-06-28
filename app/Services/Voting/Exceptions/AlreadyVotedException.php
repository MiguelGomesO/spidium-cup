<?php

namespace App\Services\Voting\Exceptions;

use Exception;

class AlreadyVotedException extends Exception
{
    public function __construct(
        string $message = 'Você já votou nesta partida.',
    ) {
        parent::__construct($message);
    }
}
