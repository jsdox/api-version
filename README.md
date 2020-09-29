

## Laravel API Versioning

<p>Versioning is the practice of creating collaborative data sharing and editing controls to ensure that your product continues to give consumers more choices without having to upgrade to the latest version</p>

<p>I’m going to show the simple way to make API versions in Laravel</p>


- **Clone the reop**
- **Setup local environmet**
- **Run migrations**

###1. Creating custom route files

We have two  API versions in the repo, go to `routes` folder, create two files:
- **api_v1.php**
- **api_v2.php**


###2. Mapping custom API route files
Go to `RouteServiceProvider` two coustom routes added under `boot` method
```
Route::prefix('api/v1')
    ->middleware('api')
    ->namespace('App\Http\Controllers')
    ->group(base_path('routes/api_v1.php'));

Route::prefix('api/v2')
    ->middleware('api')
    ->namespace('App\Http\Controllers')
    ->group(base_path('routes/api_v2.php'));

```
We have two api versions, so we added two route method, there may be more version of the APIs, so we can add more routes here.

Explaination:

`prefix('api/v1')` prefixing app version

`middleware('api')` default middlware for APIs

`namespace('App\Http\Controllers')` Poining the controller`s path to the routes files

`group(base_path('routes/api_v1.php'));` custom created route files that will server the contents to the `api/v1`


###3. Controllers

Go to `app/Http/Controllers` there are two folders names `V1 and V2` and `UsersController.php` files in these folders and `index` method in both controllers.

In `app\Http\Controllers\V1\WelcomeController.php`
```

class UsersController extends Controller
{
    public function index()
    {
        $response = [
            'success' => true,
            'message' => "Welcome to API version 1",
        ];

        return response()->json($response, 200);
    }
}
``` 

In `app\Http\Controllers\V2\WelcomeController.php`
```

class UsersController extends Controller
{
    public function index()
    {
        $response = [
            'success' => true,
            'message' => "Welcome to API version 2",
        ];

        return response()->json($response, 200);
    }
}
``` 

so here we are separating the controller folders to maintain the API versions.
means that If you have more API versions, you can create new route file with the name api_v3.php, map it to the `RouteServiceProvider`, create a separate folder under `Controllers` with name `V3`

###4. Custom routes
Go to `routes\api_v1.php`
```
Route::get('welcome', 'V1\UsersController@index');
```

Go to `routes\api_v2.php`
```
Route::get('welcome', 'V2\UsersController@index');
```

Both routes pointed to `UsersController`'s index method but with different Versions of prefixing.

###5. Default API to check active/inactive versions of the APIs

We have created a default API without prfixing the versions like `v1 or v2` to check all the availablke and active/inactive versions of the APIs, so in the front-end you can call this API on the splash screen to
check that the current version of the front-end app, is availbe and active. 

To check that API, Go the the `routes/api.php`
```
Route::get('version', 'APIVersioningController@index');
```
It is directly pointing the `app\Http\Controllers\APIVersioningController` outside the versions.

Go to the file

```
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
```
Here we are injecting `APIVersion` model to the constructer method and returning all the available versions of the APIs in `index` method.

For `APIVersion` model class, Go to `app\Models\APIVersion` 

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIVersion extends Model
{
    protected $table = 'api_versions';
    protected $hidden = ['created_at', 'updated_at'];

    public function get()
    {
        return response()->json(APIVersion::all(), 200);
    }
}
```


Here is the structure of the migrated table `api_versions` that holds app versions, api version corrosponding to the particular app verison and the status of the particular version.


| app_version  | api_version | Status |
| ------------ |:-----------:| -----:|
| 1            | v1          | false |
| 1.1          | v1          | true |
| 2            | v2          | true |
