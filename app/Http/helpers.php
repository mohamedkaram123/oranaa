<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;

if (!function_exists('success')) {
    function success($msg = "success",$data=[])
    {
        $statusMsg="success";

        return  response()->json([
            "msg"=>$msg,
            "data"=>$data,
            "statusMsg"=>$statusMsg
        ]);
    }
}


if (!function_exists('fail')) {
    function fail($msg = "error",$data=[],$status_fail=403)
    {
        $statusMsg="error";
        throw new HttpResponseException(
            response()->json(["msg"=>$msg,'data' => $data,"statusMsg"=>$statusMsg], $status_fail)
        );

    }
}

if (!function_exists('save_image')) {
    function save_image($image)
    {
            // Get the Base64-encoded image data
        $image_data = substr($image, strpos($image, ',') + 1);
        $mime_type = finfo_buffer(finfo_open(), base64_decode($image_data), FILEINFO_MIME_TYPE);
        $extension = '';
        switch ($mime_type) {
            case 'image/jpeg':
                $extension = '.jpg';
                break;
            case 'image/png':
                $extension = '.png';
                break;
            case 'image/gif':
                $extension = '.gif';
                break;
            default:
                // Handle unsupported image types here
                break;
        }

        $image = base64_decode($image_data);
        $filename = uniqid() . $extension;
        Storage::disk('public')->put('uploads/avatars/' . $filename, $image);
        $image_url = asset('public/storage/uploads/avatars/' . $filename);

        return $image_url;

    }
}


if (!function_exists('print_price')) {
    function print_price($price)
    {
        return number_format($price??0, 2) . " L.E";
    }
}

