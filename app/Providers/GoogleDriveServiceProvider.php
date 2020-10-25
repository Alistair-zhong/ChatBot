<?php

namespace App\Providers;

use League\Flysystem\AdapterInterface;

use Illuminate\Support\ServiceProvider;
use Masbug\Flysystem\GoogleDriveAdapter;
// use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            $service = new \Google_Service_Drive($client);

            $options = [];
            if (isset($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
                // $options['useDisplayPaths'] = true;
                // // $options['parameters'] = ['quotaUser' => (string)$some_unique_per_user_id];
            }

            $adapter = new GoogleDriveAdapter($service, $config['folder'], $options);

            return new \League\Flysystem\Filesystem($adapter, ['visibility' => AdapterInterface::VISIBILITY_PRIVATE]);
        });
    }

    public function register()
    {
    }


    protected function extendStorage()
    {
        $client = new \Google_Client;
        $service = new \Google_Service_Drive($client);

        \Storage::extend('google', function ($app, $config) use ($service, $client) {
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            $adapter = new GoogleDriveAdapter($service, $config['folder']);
            // $adapter = new GoogleDriveAdapter($service, $config['folderId']);

            return new \League\Flysystem\Filesystem($adapter, ['visibility' => AdapterInterface::VISIBILITY_PRIVATE]);
        });
    }
}
