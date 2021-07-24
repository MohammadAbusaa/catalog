<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CatalogController extends Controller{//this controller handles catalog server requests 
    public function write()//this function is used for testing purposes only, and to init
    {                      //the csv file used to store books info
        $file=fopen('books.csv','w');
        $data=[ //  data is stored as -> id,name,price,quantity,topic
            ['1','How to get a good grade in DOS in 40 minutes a day','60000$','10','distributed systems'],
            ['2','RPCs for noobs','400$','0','distributed systems'],
            ['3','Xen and the art of survivng Undergraduate school','250$','300','undergraduate school'],
            [
                '4',
                'Cooking for the impatient undergrad',
                '500$',
                '6',
                'undergraduate school',
            ],
        ];
        foreach($data as $field)fputcsv($file,$field);

        fclose($file);

        return redirect('read');
    }

    public function read()//this function is used for testing purposes only.
    {
        if(file_exists('books.csv')){
            $file=fopen('books.csv','r');
            $data=[];
            while (($field=fgetcsv($file))!== FALSE) {
                array_push($data,[
                    'name'=>$field[1],
                    'price'=>$field[2],
                    'qty'=>$field[3],
                ]);
            }
            fclose($file);

            return $data;
        }
        else return 'file not found!';
    }
    public function index()//this function is used to fetch and return all books in the store
    {
        $file=fopen('books.csv','r');
        $data=[];
        while (($field=fgetcsv($file))!== FALSE) {
            array_push($data,[
                'id'=>$field[0],
                'name'=>$field[1],
            ]);
        }
        fclose($file);

        return $data;
    }

    public function show(Request $request)//this function handles search requests
    {
        $data=$this->validate($request,[
            'bName'=>'required',
            'sMethod'=>'required',
        ]);
        
        if($data['sMethod']==='type'){
            $file=fopen('books.csv','r');
            $ret=[];
            while (($field=fgetcsv($file))!== FALSE) {
                if($field[4]===$data['bName']){
                    array_push($ret,[
                        'id'=>$field[0],
                        'name'=>$field[1],
                    ]);
                }
            }
            fclose($file);
            return count($ret)>0?$ret:redirect('home');
        }
        else if($data['sMethod']==='name'){
            $file=fopen('books.csv','r');
            $ret=[];
            while (($field=fgetcsv($file))!== FALSE) {
                if($field[0]===$data['bName']){
                    array_push($ret,[
                        'id'=>$field[0],
                        'name'=>$field[1],
                    ]);
                    fclose($file);
                    return $ret;
                }
            }
            fclose($file);
            return redirect('home');
        }
    }

    public function info($id)//this function returns the info for a specific book
    {
        try {
            (Int)$id;
        } catch (\Throwable $th) {
            return redirect('home');
        }

        $file=fopen('books.csv','r');
        while (($field=fgetcsv($file))!== FALSE) {
            if($field[0]===$id){
                $data=[
                    'id'=>$field[0],
                    'name'=>$field[1],
                    'price'=>$field[2],
                    'qty'=>$field[3],
                    'topic'=>$field[4],
                ];
                fclose($file);
                return $data;
            }
        }
        fclose($file);

        return redirect('home');
    }

    public function purchase($id)//this function handles update requests from order server
    {
        try{
            (int)$id;
        }
        catch(\Throwable $th){
            return 'FAILED!';
        }

        $file=fopen('books.csv','r');
        $data=[];
        while(($f=fgetcsv($file))!==FALSE){
            if($f[0]==$id)$f[3]--;
            array_push($data,$f);
        }
        fclose($file);
        $file=fopen('books.csv','w');
        foreach($data as $line)fputcsv($file,$line);
        fclose($file);
    }

}