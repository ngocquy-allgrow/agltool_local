<?php

namespace App\Http\Controllers\Tool\CheckFrontEndCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\InfoSourcecode;
use GuzzleHttp\Client;



class CheckFrontEndCodeReportController extends Controller
{
    /**
     * @param $id
     * @return Factory|View|void
     * @throws Throwable
     */
    public function report($id)
    {
        if (!$data = InfoSourcecode::find($id)) {
            return;
        }

        $name = $data->name;
        if (!$result = @json_decode($data->result)) {
            $result = [];
        }

        if (!$result_css = json_decode($data->result_css)) {
            $result_css = [];
        }

        if (!$result_perfectpixel = json_decode($data->result_perfectpixel)) {
            $result_perfectpixel = [];
        }

        if (!$date = $data->updated_at) {
            $date = '';
        }

        $check_html = false;
        foreach ($result as $result_item) {
            if($result_item->result != ""){
                $check_html = true;
                $htmlcode = file_get_contents(preg_replace("/ /", "%20", $result_item->url));
                $result_item->htmlCode = $htmlcode;
            }
        }

        $check_css = false;
        foreach ($result_css as $result_css_item) {
            if($result_css_item->result != ""){
                $check_css = true;
                $csscode = file_get_contents(preg_replace("/ /", "%20", $result_css_item->url));
                $result_css_item->cssCode = $csscode;
            }
        }

        $check_perfectpx = false;
        foreach ($result_perfectpixel as $result_perfectpixel_item) {
            if(isset($result_perfectpixel_item->url_screenshot)){
                if($result_perfectpixel_item->url_screenshot != null){
                    $check_perfectpx = true;
                }
            }
        }


        $title = $name;
        $html = view('tool.check_frontend_code.html-report', compact(
            'result', 'result_css', 'result_perfectpixel', 'title', 'date', 'check_html', 'check_css','check_perfectpx'
        ))->render();

        echo $html;

    }


    public function savefileScreenShot(Request $request){
        ini_set('max_execution_time', 0);

        $url = request()->url;
        $m_path = request()->m_path;
        $info_key = request()->info_key;
        $user_name = request()->user_name;
        $image = $request->image;

        $image_parts = explode(";base64,", $image);

        $image_base64 = base64_decode($image_parts[1]);

        $name = explode("/", $url); 
        $filename = end($name).'_'.time().'.png';

        $path = public_path('checkcodeSourcecode/'.$user_name.'/screenshot/');
        $path = str_replace('', '', $path);
        if(!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $path = $path . $filename;
        $path = str_replace('', '', $path);        

        if(File::exists($path)) {
            File::delete($path);  
        }
        File::put($path, $image_base64);
        $infoSourcecode = InfoSourcecode::find($info_key);
        if(isset($infoSourcecode->result_perfectpixel)){
            $infoSourcecode_de = json_decode($infoSourcecode->result_perfectpixel);
            foreach ($infoSourcecode_de as $key => $value) {
                if($value->url == $url){
                    $value->path_screenshot = $path;
                    $value->url_screenshot = url('/').'/checkcodeSourcecode/'.$user_name.'/screenshot/'.$filename;
                    $value->url_screenshot = str_replace('', '', $value->url_screenshot);

                    $result = $this->compare($value->url_design, $value->url_screenshot);
                    if($result){
                        $value->result = $result;
                    }
                    $infoSourcecode->result_perfectpixel = json_encode($infoSourcecode_de);
                    $infoSourcecode->save();
                }
            }
        }
        File::delete($m_path);
        return response()->json(['code' => '200', 'status' => '1']);
    }

    private function compare($url_design, $url_screenshot){
        ini_set('max_execution_time', 0);

        $design_file = $url_design;
        $web_file = $url_screenshot;

        $client = new Client();
        $response = $client->request('POST', 'http://103.77.160.168/~api/api1/public/api/compare', [
            //'auth'      => [ env('API_USERNAME'), env('API_PASSWORD') ],
            'multipart' => [
                [
                    'name'     => 'design_file',
                    'contents' => file_get_contents($design_file),
                    'filename' => 'design_file'
                ],
                [
                    'name'     => 'web_file',
                    'contents' => file_get_contents($web_file),
                    'filename' => 'web_file'
                ]
            ],
        ]);

        if($response->getStatusCode() == 200){
            $contents = $response->getBody()->getContents();
            $jsContent = json_decode($contents, true);
            if($jsContent['code'] == 1){
               return $jsContent;
            }
        }
        return null;        
    }
}
