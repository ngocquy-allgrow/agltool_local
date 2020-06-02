<?php

namespace App\Http\Controllers\Tool\CheckFrontEndCode;

use App\Helpers\IO;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Services\CheckerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;

use Zipper;
use App\InfoSourcecode;


class CheckFrontEndCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CheckerService $checkerService
    ) {
        $this->middleware('auth');
        $this->checkerService = $checkerService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;


        $info = array();
        $infoSourcecodes = InfoSourcecode::where(['user_id' => $user_id])->orderBy('created_at', 'desc')->get();
        foreach ($infoSourcecodes as $infoSourcecode) {

        	$id = $infoSourcecode->id;

            $info[$id]['name'] = explode('___',$infoSourcecode->name)[0];
            $info[$id]['date'] = $infoSourcecode->created_at->toDateTimeString();
            $info[$id]['html_validation'] = false;
            $info[$id]['css_validation'] = false;
            $info[$id]['perfectpixel'] = false;            

            if($infoSourcecode->result){                
                foreach (json_decode($infoSourcecode->result_perfectpixel) as $key => $value) {
                    $name = explode("/", $value->url);           
                    $info[$id]['result_perfectpixel'][$value->url]['name'] = end($name); 

                    if(isset($value->url_design)) $info[$id]['result_perfectpixel'][$value->url]['url_design'] = $value->url_design;
                    if(isset($value->url_design)) $info[$id]['result_perfectpixel'][$value->url]['width'] = $value->width;
                    if(isset($value->url_screenshot)) $info[$id]['result_perfectpixel'][$value->url]['url_screenshot'] = $value->url_screenshot;
                    
                    $info[$id][$value->url]['perfectpixel'] = false;                
                    if(isset($value->result) && $value->result != ""){
                       $info[$id]['result_perfectpixel'][$value->url]['result'] = $value->result;
                       $info[$id][$value->url]['perfectpixel'] = true;
                       $info[$id]['perfectpixel'] = true;
                    }                        
                }
            }

            if($infoSourcecode->result){
                $warningcount_html = 0;
                $errorcount_html = 0;
                
                foreach (json_decode($infoSourcecode->result) as $key => $value) {
                    $name = explode("/", $value->url);     
                    $info[$id]['result_html'][$value->url]['name'] = end($name);                 
                    if(is_array($value->result)){
                        $info[$id]['result_html'][$value->url]['result'] = $value->result;
                        foreach ($value->result as $msg_key => $msg_value) {
                            if($msg_value->type == "info"){
                                $warningcount_html++;
                            }
                            elseif($msg_value->type == "error"){
                                $errorcount_html++;
                            }
                        }
                        $info[$id]['html_validation'] = true;
                    }                        
                }
                $info[$id]['errorcount_html'] = $errorcount_html;
                $info[$id]['warningcount_html'] = $warningcount_html;
            }
           
            if($infoSourcecode->result_css){
                $warningcount_css = 0;
                $errorcount_css = 0;
                foreach (json_decode($infoSourcecode->result_css) as $key => $value) {
                	$name = explode("/", $value->url);  
                    $info[$id]['result_css'][$value->url]['name'] = end($name);                    
                   
                    if(isset($value->result->result)){
                        $info[$id]['result_css'][$value->url]['result'] = $value->result;
                        $errorcount_css += $value->result->result->errorcount;
                        $warningcount_css += $value->result->result->warningcount;                 
                        $info[$id]['css_validation'] = true;
                    }
                	
                }
                $info[$id]['errorcount_css'] = $errorcount_css;
                $info[$id]['warningcount_css'] = $warningcount_css;
            }
        }

        $path = public_path('checkcodeSourcecode/'.$user_name.'/design/');
        $path = str_replace(" ","",$path); 

        $file_array  = array();
        if(File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file_key => $file) {
                $url = str_replace('', '', url('/').'/checkcodeSourcecode/'.$user_name.'/design/'.$file->getFilename());
                $fileName = $file->getFilename();
                $filename = explode('___', $file->getFilename())[0];

                $file_array[] = [$filename,$url,$file->getPathname()];
            }
        }

        return view('tool.check_frontend_code.index')->with('infos', $info)->with('file_array', $file_array); 
    }

    public function uploadSource(Request $request){
    	$request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

    	ini_set('max_execution_time', 3000);

    	$currentTimeinSeconds = microtime(true);

    	request()->validate([
            'sourcecode' => 'max:1000000|mimes:zip,rar',
        ]);

        $file = request()->sourcecode;

        if(!$file){
            return back();
        }

        if($file){
            $name      = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameNoExt = basename($name, '.' . $extension);

            $fullName = $nameNoExt."___".time();

            $pathExtract = 'checkcodeSourcecode/'.$user_name.'/' . $fullName;
            $pathExtract = str_replace('', '', $pathExtract);
            $path = $file->move(public_path('zip'), $name);            

            // unzip file
            Zipper::make($path->getPathName())->extractTo($pathExtract);

            $result = array();
            $result_css = array();
            $filesInFolder = \File::allFiles($pathExtract);
            foreach($filesInFolder as $path) { 
                $file = pathinfo($path);
                if(isset($file['extension'])){
                    $url = url('/').'/'.$file['dirname'].'/'.$file['basename'];
                    $url = str_replace('', '', $url);
                    if($file['extension'] == 'php' || $file['extension'] == 'html'){
                        $result[] = [
                            'path' => $file['dirname'].'/'.$file['basename'],
                            'url' => $url,
                            'result' => ''
                        ];
                    }
                    elseif($file['extension'] == 'css'){                    
                        $result_css[] = [
                            'path' => $file['dirname'].'/'.$file['basename'],
                            'url' => $url,
                            'result' => ''
                        ];
                    }
                }                 
            }

            File::deleteDirectory(public_path('zip'));
            $encodedResult = json_encode($result);
            $encodedResult_css = json_encode($result_css);
            $infoSourcecode = InfoSourcecode::create(array('user_id' => $user_id,'name' => $fullName,'result' => $encodedResult,'result_css' => $encodedResult_css,'result_perfectpixel' => $encodedResult));
        }

    	
        return redirect(route('checkfrontendcode_index'));         
    }

    public function checkValidation(Request $request){

        $request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

        ini_set('max_execution_time', 0);
        $info_key = request()->info_key;
        $infoSourcecode = InfoSourcecode::find($info_key);

        if(request()->html_check == 1){
            $infoSourcecode_de = json_decode($infoSourcecode->result);
            foreach ($infoSourcecode_de as $key => $value) {

                $url = $value->url;

                $client = new Client();
                $response = $client->request('GET', 'http://validator.w3.org/nu/?out=json&doc='.$url);
                if($response->getStatusCode() == 200){
                    $contents = $response->getBody()->getContents();
                    $jsContent = json_decode($contents, true);
                    if($jsContent){
                        $value->result = $jsContent['messages'];                      
                    }
                }
            }
            $infoSourcecode->result = json_encode($infoSourcecode_de);
            if($infoSourcecode->result){
                $warningcount = 0;
                $errorcount = 0;                
                foreach (json_decode($infoSourcecode->result) as $key => $value) {
                    if(is_array($value->result)){
                        foreach ($value->result as $msg_key => $msg_value) {
                            if($msg_value->type == "info"){
                                $warningcount++;
                            }
                            elseif($msg_value->type == "error"){
                                $errorcount++;
                            }
                        }
                    }                        
                }                
            }
            $infoSourcecode->save();
            return ['code' => 1, 'errorcount' => $errorcount, 'warningcount' => $warningcount];            
        }

        if( request()->css_check == 1 ){
            $infoSourcecode_de = json_decode($infoSourcecode->result_css);
            foreach ($infoSourcecode_de as $key => $value) {
                $url = $value->url;
                $client = new Client();
                $response = $client->request('GET', 'https://jigsaw.w3.org/css-validator/validator?warning=0&profile=css3&output=json&lang=en&uri='.$url);
                if($response->getStatusCode() == 200){
                    $contents = $response->getBody()->getContents();
                    $jsContent = json_decode($contents, true);
                    if($jsContent){
                        $value->result =  $jsContent['cssvalidation'];
                    }
                }
            }
            $infoSourcecode->result_css = json_encode($infoSourcecode_de);

            if($infoSourcecode->result_css){
                $warningcount = 0;
                $errorcount = 0;
                foreach (json_decode($infoSourcecode->result_css) as $key => $value) {                   
                    if(isset($value->result->result)){
                        $errorcount += $value->result->result->errorcount;
                        $warningcount += $value->result->result->warningcount;                 
                    }                    
                }
            }

            $infoSourcecode->save();
            return ['code' => 1, 'errorcount' => $errorcount, 'warningcount' => $warningcount];
        }
        return redirect(route('checkfrontendcode_index'));
    }

    public function uploadFile(Request $request){
    	ini_set('max_execution_time', 0);

    	$request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

        request()->validate([
            'fileupload' => 'required',
        ]);

        $allowedfileExtension=['psd','png','jpeg','jpg'];
        $files = request()->fileupload;
        foreach($files as $file) {            
            $originalExt = $file->getClientOriginalExtension();
            $check=in_array($originalExt,$allowedfileExtension);
            if(!$check) {
                continue;
            }

            $fileName      = $file->getClientOriginalName();
            $fileName = str_replace('', '', $fileName);
            $name = explode('.', $fileName)[0];
            $name = $name.'___'.time();

            $path = 'checkcodeSourcecode/'.$user_name.'/design/'; 
            $pathExtract = str_replace('', '', $path);
            if(!File::exists($pathExtract)) {
                File::makeDirectory($pathExtract, $mode = 0777, true, true);
            }
            if($originalExt == "psd"){
    	        $file->move(public_path('checkcodeSourcecode/design/'), $fileName);  
    	        $designUrl = public_path('checkcodeSourcecode/design/') . $fileName;

            	$client = new Client();
    	        $response = $client->request('POST', 'http://103.77.160.168/~api/api1/public/api/upload-psd', [
    	            //'auth'      => [ env('API_USERNAME'), env('API_PASSWORD') ],
    	            'multipart' => [
    	                [
    	                    'name'     => 'design_file',
    	                    'contents' => file_get_contents($designUrl),
    	                    'filename' => 'design_file'
    	                ]
    	            ],
    	        ]);
    	        if($response->getStatusCode() == 200){
    	            $contents = $response->getBody()->getContents();
    	            $jsContent = json_decode($contents, true);

    	            if($jsContent['code'] == 1){
                        $urlfile = $jsContent['design_file'];
                        $contents = file_get_contents($urlfile);
                        file_put_contents(public_path( $pathExtract.$name.'.png'), $contents);
    	            }else{
    	            	return ['code'=> 0,'error' => 'can not convert design'];
    	            }
    	        }else{
                    return ['code'=> 0,'error' => 'can not convert design'];
                }
    	        File::deleteDirectory(public_path('checkcodeSourcecode/design/'));
            }else{  
                $file->move($pathExtract,$name.'.png');
            }
        }
        return redirect(route('checkfrontendcode_index'));
    }

    public function chooseDesign(Request $request){
        ini_set('max_execution_time', 0);

        $request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

        $url = $request->url;
        $info_key = request()->info_key;

        $infoSourcecode = InfoSourcecode::find($info_key);
        if($infoSourcecode->result_perfectpixel){
            $infoSourcecode_de = json_decode($infoSourcecode->result_perfectpixel);
            foreach ($infoSourcecode_de as $key => $value) {
                if($value->url == $url){
                    if(isset($request->design_info)){
                        // xu ly design
                        $design_info = $request->design_info;
                        $design_info = explode(",", $request->design_info);
                        $filename = $design_info[1];
                        $size = getimagesize($filename);
                        $width = $size[0];
                        $height = $size[1];


                        // save data
                        $value->path_design = $filename;
                        $value->url_design = $design_info[0];
                        $value->width = $width;
                        $infoSourcecode->result_perfectpixel = json_encode($infoSourcecode_de);
                        $infoSourcecode->save();


                        // xu ly screenshot
                        $path = $value->path;
                        $path = public_path($path);
                        $m_path = str_replace(".html","_m.html",$path);
                        $m_path = str_replace(".php","_m.php",$m_path);
                        $content = File::get($path);
                        $add_content = "<script src='https://html2canvas.hertzen.com/dist/html2canvas.min.js'></script>
                        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                        <script type='text/javascript' charset='utf-8'>
                           function screenshot(){
                              html2canvas(document.body,{windowWith: '".$width."', width: '".$width."', letterRendering : 1, useCORS: true, allowTaint: true}).then(function(canvas) {
                               document.body.appendChild(canvas);
                           
                               // Get base64URL
                               var base64URL = canvas.toDataURL('image/jpeg').replace('image/jpeg', 'image/octet-stream');

                               //loading html
                               var thtml = '<div style=\"line-height: 100vh; text-align:center;\"><img style=\"vertical-align: middle;\" src=\"https://cdn.dribbble.com/users/3337757/screenshots/6825268/076_-loading_animated_dribbble_copy.gif\" width=\"50%\" height=\"50%\"></div>';
                               $('body').html(thtml);
                           
                               // AJAX request
                               $.ajax({
                                  url: '".route("checkfrontendcode_save_file_screen_shot")."',
                                  type: 'post',
                                  data: {image: base64URL,info_key: ".$info_key.",url: '".addslashes($value->url)."',user_name: '".$user_name."',m_path: '".addslashes($m_path)."'},
                                  success: function(data){
                                    window.close();
                                  }
                               });
                             });  
                           }
                            window.onload = function() {
                                screenshot();
                            };

                        </script>";
                        $content = str_replace("</body>",$add_content."</body>",$content);
                        File::put($m_path, $content);

                        $value->url = str_replace(".html","_m.html",addslashes($value->url));
                        $value->url = str_replace(".php","_m.php",$value->url);
                        return '
                        <!DOCTYPE html>
                        <html>
                        <head>
                          <title>Bootstrap Example</title>
                          <meta name="viewport" content="width=device-width, initial-scale=1">
                          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
                          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
                          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
                        </head>
                        <body>

                        <div class="container" style="line-height:100vh; text-align: center">
                        <div class="spinner-border" style="width: 10rem; height: 10rem;"></div>
                        </div>
                        <script>var w = window.open("'.$value->url.'", "", "width='.$width.',height=1000");
                                var popupTick = setInterval(function() {
                                  if (w.closed) {
                                    clearInterval(popupTick);
                                    window.location.replace("'.route('checkfrontendcode_index').'");
                                  }
                                }, 500);
                        </script>
                        </body>
                        </html>
                        ';
                    }else{
                        if(isset($value->path_screenshot)){
                            File::delete($value->path_screenshot);
                        }
                        $value->url_design = null;
                         $value->path_design = null;
                        $value->width = null;
                        $value->url_screenshot = null;
                        $value->result = null;
                        $infoSourcecode->result_perfectpixel = json_encode($infoSourcecode_de);
                        $infoSourcecode->save();
                    }
                }
            }            
        }
        
        return redirect(route('checkfrontendcode_index'));
    }

    public function deleteDesign(Request $request){
        ini_set('max_execution_time', 0);

        $request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

        $path = request()->path;
        File::delete($path);

        $infoSourcecodes = InfoSourcecode::all();
        foreach ($infoSourcecodes as $infoSourcecode) {
            $infoSourcecode_de = json_decode($infoSourcecode->result_perfectpixel);
            foreach ($infoSourcecode_de as $key => $value) {

                if(isset($value->path_design)){
                    if($path == $value->path_design){
                        $value->url_design = null;
                        $value->path_design = null;
                        $value->width = null;
                        $value->url_screenshot = null;
                        $value->result = null;                       
                        $infoSourcecode->result_perfectpixel = json_encode($infoSourcecode_de);
                        $infoSourcecode->save();
                    }
                }
            }
        }
        
        
        return redirect(route('checkfrontendcode_index'));
    }

    public function compare(Request $request){

    	ini_set('max_execution_time', 0);

    	$request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

        $url = $request->url;
        $info_key = request()->info_key;        

    	$design_file = $request->url_design;
    	$web_file = $request->url_screenshot;

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
                $infoSourcecode = InfoSourcecode::find($info_key);
                $infoSourcecode_de = json_decode($infoSourcecode->result_perfectpixel);
                foreach ($infoSourcecode_de as $key => $value) {
                    if($value->url == $url){
                        $value->result = $jsContent;
                    }
                }

                $infoSourcecode->result_perfectpixel = json_encode($infoSourcecode_de);
                $infoSourcecode->save();
            }
        }

        return $jsContent;
    }

    public function delete(Request $request, $id){
        $request->user()->authorizeRoles(['admin']);
        $user_id = $request->user()->id;
        $user_name = $request->user()->email;

        if (!$infoSourcecode = InfoSourcecode::find($id)) {
            return;
        }

        $infoSourcecode = InfoSourcecode::find($id);
        if(isset($infoSourcecode->result_perfectpixel)){
            $infoSourcecode_de = json_decode($infoSourcecode->result_perfectpixel);
            foreach ($infoSourcecode_de as $key => $value) {
                if(isset($value->path_screenshot)){
                    File::delete($value->path_screenshot);
                }                
            }
        }

        $fullName = $infoSourcecode->name;
        $pathExtract = 'checkcodeSourcecode/'.$user_name.'/' . $fullName;
        $pathExtract = str_replace('', '', $pathExtract);

        File::deleteDirectory(public_path($pathExtract));
        $infoSourcecode->delete();

        return redirect(route('checkfrontendcode_index'));
    }

}
