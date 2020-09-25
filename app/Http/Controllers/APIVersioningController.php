<?php

namespace App\Http\Controllers;

use App\Models\APIVersion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class APIVersioningController extends BaseController
{
    private $api_version;
    public function __construct(APIVersion $api_version)
    {
        $this->api_version = $api_version;
    }

    public function index()
    {
        return $this->api_version->get();
    }
}
