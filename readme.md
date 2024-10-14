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
@javascript()
<script>
    document.querySelector(".my-component").innerText = "Maybe star this repo ?"  
</script>
@endjavascript

<div class="my-component"></div>

@css()
<style>
    .my-component {
        background: red;
    }
</style>
@endcss
```

And you could also use `SCSS` or `Typescript`

```blade
@javascript('/js/my-file.ts')
<script>
    let starRepo: boolean;
    
    starRepo = true;
    console.log(starRepo);
</script>
@endjavascript
@css('/css/my-file.scss')
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
@css('/optional/pathname.css')
<style>
    // Your CSS
</style>
@endcss
```

If a pathname is provided, the code will try to find or create the file in the `resource_path()` directory.

If no pathname is provided, the code will be added to the file defined in the config (`/resources/css/generated.css` by default). 
You can then import it in your main `CSS` entry point.

The `<style>` tags are optional, you can add them for better syntax highlighting.

### JS rules

The `@javascript()...@endjavascript` rules work as follow:

```blade
@javascript('/optional/pathname.js')
<script>
    // Your JS
</script>
@endjavascript
```

If a pathname is provided, the code will try to find or create the file in the `resource_path()` directory.

If no pathname is provided, the code will be added to the file defined in the config (`/resources/js/generated.js` by default). 
You can then import it in your main `JS` entry point.

The `<script>` tags are optional, you can add them for better syntax highlighting.

### The `blade-sfc:compile` command

This command is used to parse the blade files and put the JS and CSS content into the correct files.
It's used like so:

```shell
php artisan blade-sfc:compile
npm run build
```

If you want to avoid running it manually, you can use [vite-plugin-run](https://github.com/innocenzi/vite-plugin-run/tree/main).
Here's how to modify your vite config:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { run } from 'vite-plugin-run'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'], // Or whatever your CSS and JS files are
            refresh: true,
        }),
        run([
            {
 ,              name: 'compile views',
                run: ['php', 'artisan', 'blade-sfc:compile'],
                condition: (file) => file.includes('.blade.php'),
            },
        ]),
    ]
});
```

## Configuration

You can publish the package's configuration like so:

```shell
php artisan vendor:publish --tag=blade-sfc-config
```

The configuration file allows you to choose where you want to output your `JS` and `CSS` by default.

## Future improvements

- [ ] Allow for blade statements to be used inside JS and CSS. (Is currently a problem because of unknown variables at render time).
