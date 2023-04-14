<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use SimpleXMLElement;

class Attendee extends Model
{
    protected $guarded = [];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }


    public function district(){
        return $this->belongsTo(District::class);
    }


    public function ward(){
        return $this->belongsTo(Ward::class);
    }


    public function evaluationFormResponses(){
        return $this->hasMany(EvaluationFormResponse::class);
    }


    public static function generateAttendeeVerificationCode(){
        $chars = '25';
        $data = '@$1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';

        $verification_code = substr(str_shuffle($data), 0, $chars);
        $existingVerificationCodes = Attendee::where("verification_code",$verification_code)->get();
        while(count($existingVerificationCodes) >=1){
            $verification_code = substr(str_shuffle($data), 0, $chars);
            $existingVerificationCodes = Attendee::where("verification_code",$verification_code)->get();
        }

        return $verification_code;
    }


    public static function storeFiles($attendee){

        if(request()->has('image')){
            //Save image
            $imagePath = request()->image->store('attendees/images','public');
            $imageName = str_replace('attendees/images/','',$imagePath);


            $originalImage= request()->image;
            //Save passport size
            $passportImage = Image::make($originalImage);
            $passportPath = storage_path()."/app/public/attendees/passports/".$imageName;
            $passportImage->save($passportPath);
            $passportImage->resize(450,null,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $passportImage->save($passportPath);

            //Save thumbnail
            $thumbnailImage = Image::make($originalImage);
            $thumbnailPath = storage_path()."/app/public/attendees/thumbnails/".$imageName;
            $thumbnailImage->save($thumbnailPath);
            $thumbnailImage->resize(64,null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $thumbnailImage->save($thumbnailPath);

            //Update participant details
            $attendee->update([
                'image' => $imagePath,
                'passport' => 'attendees/passports/'.$imageName,
                'thumbnail' => 'attendees/thumbnails/'.$imageName,
            ]);
        }

    }



}
