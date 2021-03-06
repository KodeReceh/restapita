<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () {
    return redirect('https://esisma.efzet.id');
});

$router->get('pdf', 'LetterTemplateController@generateFromTemplate');
$router->get('testQR', 'LetterTemplateController@testQRCode');

$router->post('webhook', function (Request $request) {
    $cmd = 'cd .. && git pull origin master';
    $output = '';
    $payload = json_decode($request->payload);

    if ($commits = $payload->commits) {
        $output .= 'commits:<br><br>';
        $migrateFresh = false;
        $migrateRefresh = false;
        $migrate = false;
        $seed = false;
        $composerUpdate = false;

        foreach ($commits as $commit) {
            $output .= '- ' . $commit->message . '<br>';
            if (strpos($commit->message, 'do fresh migrate') !== false) {
                $migrateFresh = true;
            } elseif (strpos($commit->message, 'do refresh migrate') !== false) {
                $migrateRefresh = true;
            } elseif (strpos($commit->message, 'do migrate') !== false) {
                $migrate = true;
            }

            if (strpos($commit->message, 'do db:seed') !== false) {
                $seed = true;
            }

            if (strpos($commit->message, 'do composer update') !== false) {
                $composerUpdate = true;
            }

        }

        $output .= '<br><br>';

        if ($composerUpdate) $cmd .= ' && composer update';

        if ($migrateFresh) {
            $cmd .= ' && php artisan migrate:fresh && rm -rf storage/app/* && cp -r example/signatures storage/app/';
            if ($seed) $cmd .= ' && php artisan db:seed';
        } elseif ($migrateRefresh) {
            $cmd .= ' && php artisan migrate:refresh && rm -rf storage/app/* && cp -r example/signatures storage/app/';
            if ($seed) $cmd .= ' && php artisan db:seed';
        } elseif ($migrate) {
            $cmd .= ' && php artisan migrate';
            if ($seed) $cmd .= ' && php artisan db:seed';
        }
    }

    try {
        $output = 'output command: <br>' . shell_exec($cmd);
    } catch (\Throwable $th) {
        $output = $th->getMessage();
    }

    return response()->json(['output' => $output], 200);
});

