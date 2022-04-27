## Installing

```shell
$ composer require amrnrd/likeable
```

### Configuration

This step is optional

```php
$ php artisan vendor:publish --provider="AmrNRD\\Likeable\\LikeServiceProvider" --tag=config
```

### Migrations

This step is also optional, if you want to custom likes table, you can publish the migration files:

```php
$ php artisan vendor:publish --provider="AmrNRD\\Likeable\\LikeServiceProvider" --tag=migrations
```


## Usage

### Traits

#### `AmrNRD\Likeable\Traits\Liker`

```php

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use AmrNRD\Likeable\Traits\Liker;

class User extends Authenticatable
{
    use Liker;
    
    <...>
}
```

#### `AmrNRD\Likeable\Traits\Likeable`

```php
use Illuminate\Database\Eloquent\Model;
use AmrNRD\Likeable\Traits\Likeable;

class Post extends Model
{
    use Likeable;

    <...>
}
```

### API

```php
$user = User::find(1);
$post = Post::find(2);

$user->like($post);
$user->dislike($post);

$user->doLike($post); 
$user->doesnotLike($post);

$post->isLikedBy($user); 
```

Get user likes with pagination:

```php
$likes = $user->likes()->with('likeable')->paginate(20);

foreach ($likes as $like) {
    $like->likeable; // App\Post instance
}
```

Get object likers:

```php
foreach($post->likers as $user) {
    // echo $user->name;
}
```

with pagination:

```php
$likers = $post->likers()->paginate(20);

foreach($likers as $user) {
    // echo $user->name;
}
```

### Aggregations

```php
// all
$user->likes()->count(); 

// with type
$user->likes()->withType(Post::class)->count(); 

// likers count
$post->likers()->count();
```
