### Класс коллекций:
* core\collection\Collection.php
* collect([]);

```php
public function __construct(array $items = []): void;
public function set(string $key, $value): void;
public function get(string $key, $default = null): mixed|null;
public function replace(array $items): void;
public function has(string $key): bool;
public function remove(string $key): void;
public function all(): array;
public function except($keys): void;
private function exceptOne(string $key): void;
private function exceptMany($keys): void;
public function only(array $keys): Collection;
public function clear(): void;
public function count(): int;
```

### Примеры
* Инициализация, Принимает массив и вызывает replace()
```php
$data = collect();
$array = ['key' => 'value'];
$data = collect([$array]);
```

* Помещает новое свойство вида: $key => $value
```php
$data = collect();
$data->set('key', 'value');
```

* Возвращает $value по $key
```php
echo collect([])->get('key');
```

* Перебирает массив и и вызывает метод set() для каждого индекса (вид: $key => $value)
```php
$array = ['key' => 'value'];
$data = collect()->replace($array);
```

* Проверяет существования ключа
```php
return $data->has('key') ? 'true' : 'false';
```

* Удаляет ключ
```php
$data->remove('key');
```

* Возвращает все и меняет тип на массив
```php
$array = ['key' => 'value'];
$data = collect([$array]); // Object type
$data->all() // Array type
```

* Два вида:
  *  ('key') | (['key1', 'key2'])

   Возвращает все данные без ключей которые указанны выше
```php
$array = ['key' => 'value', 'key2' => 'value'];
$data = collect([$array])->except('key');
$data->get('key') // error
```

* Используется методом except()
* Используется методом except()

* Возвращает новую коллекцию с вышеперечисленными ключами
```php
$data = collect([$_SERVER])->only(['REQUEST_SCHEME', 'SERVER_PROTOCOL', 'CONTENT_LENGTH']);
dd($data);
```

* Очищает коллекцию
* Возвращает количество элементов

* Класс имеет глобальную функцию collect()


### Класс конфига
* core\config\Repository
* config()

```php
public function load(string $filepath): throw|void;
public function get(string $key): mixed
```

* Используется методом get для загрузки файла
* Принимает 3 вложения (учитывая название файла) и возвращает $value ключа
```php
config()->get('app.name') // config/app.php[name];
```


### Сервис контейнер
* core\container\ServiceContainer
* app()

```php
public static function getInstance();
public function set(string $key, $object, $singleton = false): $this|void;
public function singleton($key, $object): void;
public function createAlias(string $key, string $alias): mixed;
public function bildClass(string $key, $params = null): mixed;
public function onlyLoadClass(array $classes): void;
private function instance($key, $parameters = null): mixed;
```

* Возвращает объект класса
* Добавляет новый класс
```php
app()->set('Router', '\core\routers\Router');
```

* Добавляет синглтон класс
* Создает алиас | можно записать класс в config/app.php aliases
```php
app()->set('name', '\core\name\system')->createAlias('name', 'aliasName');
```

* Возвращает объект класса
```php
$request = app()->bildClass('request'); // Если класс уже загружен (указан в config/app.php)
$requst = app()->set('request', '\core\request\Request')->bildClass('request');
```

* Используется системой
* Используется для загрузки объектов


### Куки
* core\cookie\Cookie
* cookie()

```php
public function __construct(): Collection;
public function set($key, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httponly = true): void;
public function get($key): string;
public function has($key): bool;
public function remove($key): void;
public function getData(): mixed|object;
public function __debugInfo();
```

* Возвращает все куки
* Записывает куку | для значения используется обратное шифрование
```php
cookie()->set('login', 'DeadMemories');
```

* Возвращает куку
```php
cookie()->get('login');
```

* Проверяет на существования
```php
echo cookie()->has('login') ? 'true' : 'false';
```

* Удаляет куку
```php
cookie()->remove('login');
```

* Возвращает все куки | Collection ojbect


### Помощники
* core\helpers\function
* Помогают Вам не писать большие use :)

```php
function app() {
    return \core\container\ServiceContainer::getInstance();
}

function config() {
    return app()->set('config', '\core\config\Repository')->bildClass('config');
}

function route(){
    return app()->bildClass('Route');
}

function cookie() {
    return app()->bildClass('Cookie');
}

function collect(array $data = []) {
    return app()->bildClass('Collection', $data);
}

function response() {
    return app()->bildClass('Response');
}

function view($path, $data = [], $type = '') {
    return app()->bildClass('View')->showView($path, $data, $type);
}

function request() {
    return app()->bildClass('Request');
}

function dd() {
    $args = func_get_args();
    call_user_func_array('dump', $args);
    die();
}
```

### Request
* core\request\Request
* request()

```php
public function __construct(): void;
public function input($name, $default = null): mixed;
public function has(string $name): bool;
public function getAll(): mixed;
private function cleanData($data): string;
```

* Помещает в только что созданный объект $request 3 объекта(response, cookie, uploadFIled) и записывает все данные
* Возвращает веденное значение.
  * Два вида
  ```php
    $request->input('name');
    $request->input(['name1', 'name2']);
  ```

* Проверка на существование
```php
echo $request->has('key') ? 'true' : 'false',
```

* Возвращает все данные
* Используется системой


### UploadFiles - помощник для реквеста. Работает с файлами.

Когда загружается файл, объект $request->uploadFiles содержит коллекцию загруженных файлов.
Вы можете использовать foreach, чтобы узнать подробную информацию

```php
public function getFiles(): mixed;
private function getAllFiles($file_keys, $file_count, $file_post, $name): mixed;
private function getFile($data, $name): mixed;
```


### Response
* core\response\Respose
* response()

```php
public function __construct(): void;
public function getData(): mixed;
public function changeResponseCode(int $code): void;
public function getStatus(): mixed;
public function getContent(): mixed;
public function setContent($key): $this;
private function setContents($keys): void;
public function setHeader($key): $this;
private function setHeaders($keys): void;
public function relocation(string $url): void;
public function json($data, $code = 200): mixed;
public function __debugInfo();
```

* Помещает в себя всю нужную информацию
* Возвращает всю информацию
* Изменяет код ответа
```php
response()->changeResponseCode(200);
```

* Возвращает статус
* Возвращает контент
* Устанавливает контент
```php
response()->setContent('content');
response()->setContent([''content', 'content1']);
```

* Устанавливает контент
* Устанавливает хеадер | аналогия с контентом
* Используется для хеадера
* Переадресация
* Возвращает json


### Router
* core\routers\Router;
* router()

```php
public static function __callStatic($func, $args);
public function __call($func, $args): Exception;
public function getRequestMethod(): string;
public function getCurrentUrl(): string;
```

Используется, чтобы задать роутеры:
{integer}, {string}, {any}
```php
Route::get('/', function() {
    echo 'main';
});

Route::get('/user/id{integer}', '\app\controllers\IndexController@userProfile'); // userProfile($id)
```


### Base Route
```php
public static function get(...$arguments): void;
public static function post(...$arguments): void;
private static function addRouter(string $method, $arguments): void;
public function initRouters(): void;
public function startRoute(): mixed;
private function initRout($matches): mixed;
private function initNotFoundRout(): string; // 404 not found
```


### Валидация
* core\validate\Validate

```php
public function rules($request, array $rules): void;
private function callMethod($data, $params, string $key): void;
public function min($data, string $length, string $input): void;
public function max($data, string $length, string $input): void;
public function required($data, string $input): bool;
public function getErrors(): array;
public function isValid(): bool;
```

Все ошибки валидации содержатся в config/ru-validate.php. Чтобы изменить язык, Вам нужно создать новый файл:
eng-validate.php, зайти в config/app.php и изменить validate_errors на название вашего файла.


Использовать валидацию можно так:
```php
$request = new \Request;

$validate = new Validate;

$validate->rules($request->getAll(), [
        'login'    => 'required|min:5|max:15',
        'password' => 'required|min:2|max:10',
    ]
);

if ($validate->isValid()) {
    echo 'success';
} else {
    dd($validate->getErrors());
}
```


### Views
* core\views\View;
* function view($path, $data = [], $type = '')

Шаблоны используют twig. Функция view() принимает: путь к файлу, дата и тип. Так как твиг понимает практически все типы,
то Вы можете использовать различные расширения: html, php, twig и т.п. По умолчанию тип .html. Изменить можно в config/app.php[types_file]
Вложенность описывается через . (точку)

```php
view('index', ['login']);
$data = 'name';
view('user.profile', compact('data'), 'php'); // resources/views/user/profile.php
```


### Harry | Potter
* core\harry\Potter;

Harry является подобием eloquent
Методы описывать не буду, так как их много.

В модели можно указать название таблицы protected $table = 'users'
Если название не указанно, то будет применено : 'model' + 's'

Примеры использования:
```php
$user = new User;
$user->login = 'DeadMemories';
$user->save(); // Создает новую запись
$user->login // Возвращает логин из созданной записи
```

* Обновление
```php
$user = new User;
$user->findId(1);
$user->login = 'New login';
$user->update();

$user->login; // вернет 'new login'
```

* Выборка
```php
$user = new User;
$data = $user->select('users.id', 'users.login', 'image.src as image_path')
            ->leftJoin('images as image', function($q) {
                $q->on('image.user_id', '=', 'users.id')->where('image.status', '=', 1);
            })
            ->get();

$data1 = $user->all();
$user->findId(2);
$user->login;
```

* Удаление
```php
$user->delete(2);
```
