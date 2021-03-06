<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(App\Startup::class,function($app){
        //     error_log('register');
        //     return new App\Startup();
        // });
    }

    public function boot()
    {
        $arr=[];
        try {
            $client=new Client();

            $res=$client->get('192.168.1.21:7999/info');
            $data=json_decode($res->getBody());
    
            $file=fopen('books.csv','r');
            while(($content=fgetcsv($file))!==FALSE)$arr+=$content;
            fclose($file);
            foreach($data as $val){
                foreach($arr as $val2){
                    if($val[0]===$val2[0])
                        $val2[3]=$val[3];
                }
            }
        } catch(ConnectException $t){
            error_log(GuzzleHttp\Psr7\Message::toString($t->getRequest()));
        }
        catch(\Throwable $e){
            error_log($e->getMessage());
        }

        try {
            $res=(new Client())->get('192.168.1.21:7998/info');
            $data=json_decode($res->getBody());
    
            foreach($data as $val){
                foreach($arr as $val2){
                    if($val[0]===$val2[0])
                        $val2[3]=$val[3];
                }
            }
        } catch(ConnectException $t){
            error_log(GuzzleHttp\Psr7\Message::toString($t->getRequest()));
        }
        catch(\Throwable $e){
            error_log($e->getMessage());
        }

        if(!empty($arr)){
            $file=fopen('books.csv','w');
            foreach($arr as $i)fputcsv($file,$i);
        }
    }
}
