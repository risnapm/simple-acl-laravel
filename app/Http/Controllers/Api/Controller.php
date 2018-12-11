<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    protected $statusCode = 200;
    protected $statusMessage = 'Success';
    protected $meta = [];
    protected $requestIncludes;

    public function __construct()
    {
        $this->fractal = new Manager;

        // Are we going to try and include embedded data?
//        $this->requestIncludes = explode(',', Request::get('include'));
//        $this->fractal->parseIncludes($this->requestIncludes);
    }

    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    public function setStatusMessage($statusMessage)
    {
        $this->statusMessage = $statusMessage;

        return $this;
    }

    public function parseIncludes($include)
    {
        $bodyIncludes = [];

        if (is_string($include)) {
            $bodyIncludes = explode(',', $include);
        } else if (is_array($include)) {
            $bodyIncludes = $include;
        }

        $this->fractal->parseIncludes(array_unique(array_filter(array_merge($this->requestIncludes, $bodyIncludes))));

        return $this;
    }

    protected function respondWithItem($item, $callback, $include = [])
    {
        $data = $this->rawItem($item, $callback, $include);

        return $this->respondWithArray($data);
    }

    protected function respondWithCollection($collection, $callback, $include = [])
    {
        $data = $this->rawCollection($collection, $callback, $include);

        return $this->respondWithArray($data);
    }

    protected function respondWithArray(array $array, array $headers = [])
    {
        $response = response()->json($array, $this->statusCode, $headers);

        return $response;
    }

    protected function rawCollection($collection, $callback, $include = [])
    {
        $resource = new Collection($collection, $callback);
        if ($this->meta) {
            $resource->setMeta($this->meta);
        }
        if (count($include) > 0) {
            $this->parseIncludes($include);
        }
        $rootScope = $this->fractal->createData($resource);

        return $rootScope->toArray();
    }

    protected function rawItem($item, $callback, $include = [])
    {
        $resource = new Item($item, $callback);
        if ($this->meta) {
            $resource->setMeta($this->meta);
        }
        $rootScope = $this->fractal->createData($resource);

        return $rootScope->toArray();
    }
}
