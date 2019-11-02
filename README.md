# SimplePaginator

Ridiculously easy plug-and-play PHP pagination class for rapid development.
SimplePaginator is intended for use with [Twig](https://twig.symfony.com/) but
can also be used without a template engine. sets up and encapsulates all variables
needed for pagination, including navigation link hrefs on instantiation. Comes
with a [Bootstrap 4](https://getbootstrap.com/)-based pagination nav right out
of the box so you don't have to spend time building one.

## Getting Started

SimplePaginator is extremely easy to get up and running. To install, open Bash or your
terminal of choice and change into the `src` directory in your project:

```
$ cd [path/to/src]
```
Then run:
```
$ git clone https://github.com/michaelacook/SimplePaginator.git
```
Alternatively, you can download the repository as a `.zip` file and extract it to
your `src` directory.

### Prerequisites
* [Bootstrap 4](https://getbootstrap.com/)
* [Twig](https://twig.symfony.com/) (Optional)
* [Composer](https://getcomposer.org/) (Highly recommended)
* [Git](https://git-scm.com/) (Highly recommended)

### Setup
(**note:** these instructions assume use of the Slim 3 framework, but the same principles
    should apply regardless of the framework you are using. Keep in mind you may need to
    adapt these instructions to your particular case.)
1. After adding the source files to your project's `src` directory,
add the `SimplePaginator` namespace to your project's autoloader in `composer.json`:

```
"autoload": {
    "psr-4": {
        "SimplePaginator\\": "path/to/SimplePaginator/src"
    }
}
```

You may need to then run `composer dump-autoload` for the `SimplePaginator` class
to autoload.

2. If you are using Twig, add the `nav.twig` file to your `templates` directory.

3. Instantiate the `SimplePaginator` class in your controller:

```php
<php


use SimplePaginator\SimplePaginator\SimplePaginator as Paginator;


class ExampleController
{

    public function render($request, $response, $args)
    {
        // An example variable representing dynamic data
        $data = $exampleModel->getData();

        $paginator = new Paginator($this->view, $data, 10);

        return $this->view->render($response, 'test.twig', $args);
    }
}
```

The `SimplePaginator` class requires three arguments: `$view`, `$data`, and `$itemsPerPage`:
- The `$view` argument must either be the Twig view object, or `false`. `$view` has a
default value of `false`, so if you are not using Twig then omit this argument.
- If you are using Slim 3, `$view` will be the `Slim\Views\Twig` object registered on
the `$container`.
- `$data` is the data to be paginated and displayed dynamically. This could be the
results of a database query or other application data to be displayed in the view.
`$data` must be an `array`.
- `$itemsPerPage` is simply how many items from `$data` will be displayed on one
page. `$itemsPerPage` must be an `int`.

4. Use the `getPage()` method to get the dynamic data to be displayed and pass it to your view:

```php
$args['data'] = $paginator->getPage();

return $this->view->render($response, 'test.twig', $args);
```

5. Loop through your data and render according to your needs. If you are using
Twig, then below your loop, include the `nav.twig` file:

```php
<ul>
    {# loop through data to render #}
    {% for item in data %}
    <li>{{ item }}</li>
    {% endfor %}
</ul>

{{ include('nav.twig') }}
```

If you are not using Twig, you can use the `getNavHtml()` method to render the
pagination nav:

```php
<?php
// Instantiate SimplePaginator object

$data = $paginator->getPage();

$nav = $paginator->getNavHtml();
?>
<body>
    <ul>
        <?php
        foreach ($item in $data) {
            // render your data
        }
        ?>
    </ul>

    <div class="container">
        <?php echo $nav; ?>
    </div>
</body>
```

6. Add `?page=1` as a query string to the url for your page and any anchor tags that link to it. Without
this step, SimplePaginator can't do it's magic.

Viola! You should be able to page through your dynamic data with a simple Bootstrap
pagination nav.

## Built With
* [Bootstrap 4](https://getbootstrap.com/)
* [Twig](https://twig.symfony.com/)
* Laziness - I hate setting up pagination from scratch

## Contributing
If you find a bug or a way to make this component better in any way, please feel
free to make a pull request!

## Authors
* Michael Cook

## License
This project is licensed under the MIT License - see [LICENSE.md](LICENSE.md) for details
