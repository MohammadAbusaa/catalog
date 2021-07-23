<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CatalogController extends Controller{
    public function write()
    {
        $file=fopen('books.csv','w');
        $data=[ //  data is stored as -> id,name,price,quantity,topic
            ['1','become pro in dos','60000$','10','distributed systems'],
            ['2','beat dos in 40 mins a day','400$','0','distributed systems'],
            ['3','learn python','250$','300','undergraduate school'],
            [
                '4',
                'from zero to hero; complete tutorial for ruby',
                '500$',
                '6',
                'undergraduate school',
            ],
        ];
        foreach($data as $field)fputcsv($file,$field);

        fclose($file);

        return redirect('read');
    }

    public function read()
    {
        if(file_exists('books.csv')){
            $file=fopen('books.csv','r');
            $data=[];
            while (($field=fgetcsv($file))!== FALSE) {
                array_push($data,[
                    'name'=>$field[0],
                    'price'=>$field[1],
                    'qty'=>$field[2],
                ]);
            }
            fclose($file);

            return $data;
        }
        else return 'file not found!';
    }
    public function index()
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

    public function show(Request $request)
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
            return $ret;
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

    public function info($id)
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

}