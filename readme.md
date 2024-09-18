# Blade SFC

I really like the way javascript frameworks like [Vue](https://vuejs.org/) or [Svelte](https://svelte.dev/) structure their templates, it looks sort of like this:

```html
<script>
    document.querySelector(".my-component").innerText = "Maybe star this repo ?"  
</script>

<div class="my-component"></div>

<style>
    .my-component {
        background: red;
    }
</style>
```

But I hate using javascript for dealing with backend stuff so I use [Laravel](https://laravel.com/).

And that's why I created blade directives to use [Blade components](https://laravel.com/docs/11.x/blade#main-content) like you would Svelte or Vue ones. (You can read more about it [here](https://theoo.dev/en/articles/blade-sfc))

```blade
@js()
<script>
    document.querySelector(".my-component").innerText = "Maybe star this repo ?"  
</script>
@endjs

<div class="my-component"></div>

@css()
<style>
    .my-component {
        background: red;
    }
</style>
@endcss
```

One of the great benefits of this approach is that you can use `PHP` inside your `JS` or `CSS` like so:

```blade
@css()
<style>
    .profile-picture {
        background-image: url({{ storage_path($user->profile_picture) }});
    }
</style>
@endcss
```

And you could also use `SCSS` or `Typescript`

```blade
@js('resources/js/my-file.ts')
<script>
    let starRepo: boolean;
    
    starRepo = true;
    console.log(starRepo);
</script>
@endjs
@css('resources/css/my-file.scss')
<style>
    $color: red;
    .profile-picture {
        color: $color;
    }
</style>
@endcss
```

## Requirements

The blade-sfc package requires PHP 8.0+, Laravel 9+.

## Installing 

You can install the package via composer:

```shell
composer require theokbokki/blade-sfc
```

Then add `BladeSfcServiceProvider` to your list of service providers in `bootstrap/app.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    Theokbokki\BladeSfc\BladeSfcServiceProvider::class,
];
```

## Using

### Css rules

The `@css()...@endcss` rules work as follow:

```blade
@css('optional/pathname.css')
<style>
    // Your CSS
</style>
@endcss
```

If no pathname is provided, the code will be added to a `/resources/css/generated.css` file that you can then import in your main `CSS` entry point.
The `<style>` tags are optional, you can add them for better syntax highlighting.


### JS rules

The `@js()...@endjs` rules work as follow:

```blade
@js('optional/pathname.js')
<script>
    // Your JS
</script>
@endjs
```

If no pathname is provided, the code will be added to a `/resources/js/generated.js` file that you can then import in your main `JS` entry point.
The `<script>` tags are optional, you can add them for better syntax highlighting.

### The `blade-sfc:compile` command

The package also provides a useful `php artisan blade-sfc:compile` command to allow you to generate the `CSS` and `JS` files before building.
It can also be helpful for debuging as it will throw an error if the blade files can't compile.

```shell
php artisan blade-sfc:compile
npm run build
```

## Configuration

You can publish the package's configuration like so:

```shell
php artisan vendor:publish --tag=blade-sfc-config
```

The configuration file allows you to choose where you want to output your `JS` and `CSS` by default.

## Future improvements

- [ ] Put all JS and CSS in files at once to avoid multiple reloads.
