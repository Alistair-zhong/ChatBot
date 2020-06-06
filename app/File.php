<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'uploads';
    protected $fillable = [
        'cloudId','filename','type','size'
    ];
    protected $guarded = [];

    public function wigetData(){
        return [
            'file_id'   => $this->id,
            // 'url'       => 
            'type'      =>  $this->type
        ];
    }

    public static function createFromData(array $data){
        return static::create(static::parseFileData($data));
    }

    public static function parseFileData($data){
        return [
            'cloudId'   => $data['virtual_path'],
            'filename'  => $data['filename'] . "." . $data['extension'],
            'type'      => $data['type'],
            'size'      => $data['size'],
        ];
    }
}
