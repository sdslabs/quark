# Quark

This is a Laravel package that provides a code application for building platforms to host competitions.

## Setup

- Create and setup a new Laravel app

- Add the followwing repositories to the `composer.json`'s `repositories` field:
```JSON
"repositories": [
	{
		"type": "vcs",
		"url": "git@github.com:sdslabs/quark",
		"no-api": true

	},
	{
		"type": "vcs",
		"url": "git@github.com:sdslabs/falcon.git",
		"no-api": true
	}
],
```

- Add Quark as a dependency by adding `"sdslabs/quark": "dev-laravel-package"`
to `require` field in `composer.json`

- Add Falcon as a dependency by adding `"sdslabs/falcon": "dev-composer-pkg"`
to `require` field in `composer.json`

- Run

		$ composer update sdslabs/*

- Add `SDSLabs\Quark\QuarkServiceProvider::class,` under
`* Package Service Providers...` to `config/app.php`.

- Remove the migration file for `create_users_table` and `password_resets_table`
from `database/migrations`.

- Add `$this->call(SDSLabs\Quark\Database\Seeds\DatabaseSeeder);` to
`DatabaseSeeder` under `database/seeds`.

- Add following config to `.env.example` and also to `.env`:
```bash
FALCON_CLIENT_ID=client_id
FALCON_CLIENT_SECRET=secret
FALCON_URL_ACCESS_TOKEN=http://falcon.sdslabs.local/access_token
FALCON_URL_RESOURCE_ONWER_DETAILS=http://falcon.sdslabs.local/users/
FALCON_ACCOUNTS_URL=http://arceus.sdslabs.local/
FALCON_SCOPES=email,image_url
```
- `ORGANIZATIONS_ALLOWED` in `.env.example` can be provided in master app's `.env`

- Update `config/auth.php`:
	- Update the `defaults.guard` to `falcon`
	- Delete the section on `guards`

- Run the migrations with:

		$ php artisan migrate

- Run the seeds with:

		$ php artisan db:seed

- Go outside and do a dance ;)

## Extending the models in the application

To extend any of the default models, first create the new model.

Then add a line like `$this->app->bind(QuarkCompetition::class, AppCompetition::class);`
to `AppServiceProvider.php`'s register method.

## Extending the controllers in the application

Just override the router with your controller's methods

## General instructions for this project and the application

- Don't explicitly call the static methods of any class like
`Competition::get()` or `SomethingHelper::help()`. Instead use the [IoC](https://laravel.com/docs/5.3/container#resolving) container or [Facades](https://laravel.com/docs/5.3/facades)/[Contracts](https://laravel.com/docs/5.3/contracts) .  
e.g. `App::resolve(Competition::class)->get()`

- Don't initialise the classes manually like `new Competition()`. Instead use
IoC container's `make` method.

- The above practices makes it easier to swap the underlying classes, i.e. the
classes used by Quark can be extended by the application and the extended
classes will be used inside Quarks's classes/

## Working on Quark

- First you need an application. If you don't have one already setup, setup a
demo application following the above instructions
- Symlink the `vendor/sdslabs/quark` folder to your local clone of quark.

## To generate documentation

- Dowload phpDocumentor.phar
- Execute `php phpDocumentor.phar`
- To view documentation, Run `php -S localhost:8123 -t docs/api/` & Open http://localhost:8123

