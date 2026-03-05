<?php

namespace Tigusigalpa\YandexSpeechKit\Laravel;

use Illuminate\Support\ServiceProvider;
use Tigusigalpa\YandexCloudClient\YandexCloudClient;
use Tigusigalpa\YandexSpeechKit\YandexSpeechKitClient;

class YandexSpeechKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/yandex-speechkit.php', 'yandex-speechkit');

        $this->app->singleton(YandexSpeechKitClient::class, function ($app) {
            $cloudClient = new YandexCloudClient(config('yandex-speechkit.oauth_token'));
            
            return new YandexSpeechKitClient(
                cloudClient: $cloudClient,
                folderId: config('yandex-speechkit.folder_id'),
                apiKey: config('yandex-speechkit.api_key'),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/yandex-speechkit.php' => config_path('yandex-speechkit.php'),
            ], 'yandex-speechkit-config');
        }
    }
}
