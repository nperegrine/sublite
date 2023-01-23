<?php

namespace App\Http\Responses;

use Illuminate\Http\Response as HttpStatusCode;

/**
 * Class StdEmptyResponse.
 */
class StdEmptyResponse implements StdEmptyResponseInterface
{
    /**
     * Stores the status code that will be outputted.
     * Override in child classes to change the value.
     *
     * @var int
     */
    protected $statusCode = HttpStatusCode::HTTP_OK;

    /**
     * Initialize with a status code.
     *
     * @param null|int $statusCode
     */
    public function __construct(?int $statusCode = null)
    {
        if (! is_null($statusCode)) {
            $this->statusCode = $statusCode;
        }
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
