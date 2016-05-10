# Laravel service provider helper
Finds all service providers in your project and lets you add them easily to your app.php config file

## Installation

Add (for the last time!) the service provider

```
Synga\ServiceProviderHelper\ServiceProviderHelperServiceProvider::class
```

to your app.php config file.

Add the following line to you composer.json file. Make sure it is the last entry in the `post-update-cmd`

```
"post-update-cmd": [
    ...
    "Synga\\ServiceProviderHelper\\Command\\AddServiceProviderComposerCommand::addServiceProvider"
]
```

After you add the service provider to your app.php config file you need to run:

```
php artisan vendor:publish
```

From this moment, everytime you do a composer update this package checks if there are new service providers. When there
are new service providers it will ask if you want to add them. You can add multiple service providers at once. The service providers are added after you choose `exit`.

## Usage

This package can also be used as a standalone CLI command.

```
php artisan service-provider:add
```

You can add the `--composer` or `-c` option to run the command in composer mode (which will only add new service providers)

##Acknowledgements:
- When you add a service provider, your app.php config file will have a slightly different formatting. I'm busy with finding a solution

## Problems
If you get an error after composer update, make sure you have the latest version of composer. You can run 

```
composer self-update
```
