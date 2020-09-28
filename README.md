# Quark

This is a Laravel package that provides a code application for building platforms to host competitions.

## Documentation

The entire documentation is available at: https://quark.sdslabs.co

## Setup

- Create and setup a new Laravel app

- Add the folowwing repositories to the `composer.json`'s `repositories` field:
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

## How can I contribute?

Read [here](CONTRIBUTING.md) to know our contribution guidelines. Ping us at chat.sdslabs.co to get guidance. You can start with setting up Quark on your machine and try solving a few bugs listed here: https://github.com/sdslabs/quark/issues
You can also have a look at the sample app [sdslabs/laplace-no-ma](https://github.com/sdslabs/laplace-no-ma) that uses Quark.

## License

This project is under the MIT license

## Community Support

<img src="https://avatars1.githubusercontent.com/u/3220138?v=3&s=120" align="right" />
If you are interested in talking to the SDSLabs team, you can find us on our <a href="https://discord.gg/psEZWvY">SDSLabs Open Source Discord Server</a>. Feel free to drop by and say hello. You'll find us posting about upcoming features and beta releases, answering technical support questions, and contemplating world peace.

<p align=center>Created by SDSLabs with :heart:</p>
