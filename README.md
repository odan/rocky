<div align="center">
  <img src="https://user-images.githubusercontent.com/781074/191797474-c2207979-4045-40c4-b093-dc95158eb564.jpg" width=250>
  
  A micro framework skeleton for PHP. 
</div>

## Installation

Run the following commands to create a new project:

```
curl --output rocky.zip https://codeload.github.com/odan/rocky/zip/refs/heads/master
mkdir my-project
tar -xf rocky.zip --strip-components=1 -C my-project
cd my-project/
composer update
```

Note: These commands work on Linux and Windows.

Start the app:

```
composer start
```

Open: <http://localhost:8080>

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
