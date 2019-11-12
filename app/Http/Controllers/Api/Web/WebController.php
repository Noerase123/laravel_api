<?php

namespace App\Http\Controllers\Api\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebController extends Controller
{

    public function store (Request $request)
    {
        $editor_content = $request->content;
        $dom = new DomDocument('1.0','UTF-8');

        libxml_use_internal_errors(true);

        $dom->loadHtml($editor_content);
        $images = $dom->getElementByTagName('img');

        foreach ($images as $k => $img) {

            $data = $img->getAttribute('src');
            list($type, $data) = explode(';', $data);
            $data = base64_decode($data);

            $image_name = "/uploads/". 'post_' . time() . $k . '.png';

            $path = public_path() . $image_name;
            file_put_contents($path, $data);

            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }

        $editor_content_save = $dom->saveHTML();
    }
}