# Laravel service provider helper
Finds all service providers in your project and lets you add them easily to your app.php config file

## Installation

Add (for the last time!) the service provider `Synga\ServiceProviderHelper\ServiceProviderHelperServiceProvider::class` to your app.php config file.

Add the following line to you composer.json file. Make sure it is the last entry in the `post-update-cmd`

`"post-update-cmd": [
    ...
    "Synga\\ServiceProviderHelper\\Command\\AddServiceProviderComposerCommand::addServiceProvider"
]``

From this moment, everytime you do a composer update this package checks if there are new service providers. When there
are new service providers it will ask if you want to add them.

## Usage

This package can also be used as a standalone CLI command.

`php artisan service-provider:add`

You can add `--composer` or `-c` option to run in composer mode (which will only add new service providers)

##Acknowledgements:
- When you add a service provider, your app.php will have a slightly different formatting. I'm busy with finding a solution