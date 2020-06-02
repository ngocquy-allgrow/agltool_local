<?php $__env->startSection('content'); ?>
<?php if( Auth::user()->roles->first()->name =='admin' ): ?>
<div class="container-fluid">
 <div class="row">
  <div class="col-9">
     <div class="main-content row justify-content-center">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header" style="text-align: center;">
                      <a class="btn btn-secondary float-left" href="<?php echo e(url('/')); ?>">BACK</a>
                      <a style="font-size: 1.5rem;">MANAGER SOURCE CODE</a>
                      <button id="btnUploadSource" type="button" class="btn btn-primary float-right">UPLOAD NEW SOURCE</button>
                      <form class="form-update-screenshot" action="<?php echo e(route('checkfrontendcode_upload')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> 
                        <input class="sourcecode_upload" type="file" accept=".zip,.rar,.7zip" name="sourcecode" style="display:none" onchange="submitForm(this.form)"> 
                      </form>
                  </div>
                  <div class="card-body">
                      <table class="table table-hover table-chatwork_translate_v2">
                          <thead>
                              <tr class="text-center">
                                  <th class="">ID</th>
                                  <th class="text-left">Name</th>
                                  <th class="text-left">Perfect pixel</th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $infos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info_key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="text-align: center;">
                              <td><?php echo e($info_key); ?></td>
                              <td class="text-left">                              
                                <ul class="list-group">
                                  <li class="list-group-item">
                                    <?php echo e($info['name']); ?>

                                  </li>
                                  <li class="list-group-item">
                                    <div class="row text-center">
                                      <div class="col-sm border-secondary border-right" style="width: 223px;">
                                        <?php if(isset($info['result_html'])): ?>                                                                                                 
                                          <?php if($info['html_validation'] == true): ?>
                                            <div class="text-left">
                                              <strong><?php echo e(count($info['result_html'])); ?> html pages</strong><br>
                                              <strong><?php echo e($info['errorcount_html']); ?> <span class="text-danger"> errors</span></strong><br>
                                              <strong><?php echo e($info['warningcount_html']); ?> <span class="text-warning"> warnings</span></strong><br>
                                            </div>  
                                          <?php else: ?>                                                                                  
                                            <form method="post" action="<?php echo e(route('checkfrontendcode_checkvalidation')); ?>" class="text-center validation_form">
                                              <?php echo csrf_field(); ?> 
                                              <input type="hidden" name="info_key" value="<?php echo e($info_key); ?>">
                                              <input type="hidden" name="html_check" value="1">
                                              <input type="hidden" name="css_check" value="0">                                  
                                              <button class="btn btn-outline-primary">Check <span id="num"><?php echo e(count($info['result_html'])); ?> html pages</span></button>
                                            </form>                                                                                
                                          <?php endif; ?>                                
                                        <?php endif; ?>
                                      </div>
                                      <div class="col-sm" style="width: 223px">                                        
                                          <?php if(isset($info['result_css'])): ?>  
                                            <div class="text-left">                             
                                              <?php if($info['css_validation'] == true): ?>
                                              <strong><?php echo e(count($info['result_css'])); ?> css files</strong><br>
                                              <strong><?php echo e($info['errorcount_css']); ?> <span class="text-danger"> errors</span></strong><br>
                                              <strong><?php echo e($info['warningcount_css']); ?> <span class="text-warning"> warnings</span></strong><br>
                                            </div>
                                            <?php else: ?>
                                              <form method="post" action="<?php echo e(route('checkfrontendcode_checkvalidation')); ?>" class="text-center validation_form">
                                                <?php echo csrf_field(); ?> 
                                                <input type="hidden" name="info_key" value="<?php echo e($info_key); ?>">
                                                <input type="hidden" name="html_check" value="0">
                                                <input type="hidden" name="css_check" value="1">
                                                <button class="btn btn-outline-primary">Check <span id="num"><?php echo e(count($info['result_css'])); ?> css files</span></button>
                                              </form>                                          
                                            <?php endif; ?>
                                          <?php endif; ?>                                        
                                      </div>
                                    </div>
                                  </li>
                                  <li class="list-group-item text-center">
                                    <div class="row">
                                      <div class="col-sm">
                                          <a class="btn btn-success"  target="_blank" href="<?php echo e(route('checkfrontendcode_report',['id' => $info_key])); ?>">Report</a>
                                      </div>
                                      <div class="col-sm">
                                          <a class="btn btn-danger" href="<?php echo e(route('checkfrontendcode_delete',['id' => $info_key])); ?>" onclick="deleteAlert()">Delete</a>
                                      </div>
                                    </div>                                                                 
                                  </li>
                                </ul>
                              </td>
                              <td class="text-left">
                                <ul class="list-group">
                                  <?php if(isset($info['result_perfectpixel'])): ?>
                                    <?php $__currentLoopData = $info['result_perfectpixel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result_key => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <?php if($info[$result_key]['perfectpixel'] == true): ?>
                                        <?php if( round($result['result']->rate,0) > 90 ): ?>
                                          <li class="list-group-item list-group-item-success border-success">
                                        <?php elseif( round($result['result']->rate,0) > 80 ): ?>
                                          <li class="list-group-item list-group-item-warning border-warning">
                                        <?php else: ?>
                                          <li class="list-group-item list-group-item-danger border-danger">
                                        <?php endif; ?>                                      
                                      <?php else: ?>
                                          <li class="list-group-item border">
                                      <?php endif; ?>                                          
                                            <div class="row text-center">
                                              <div id="form-compare-result" class="col-sm text-left" style="width: 210px;">
                                                <a href="<?php echo e($result_key); ?>" target="_blank"><?php echo e($result['name']); ?></a>
                                                <br>
                                                <div style="display: inline-block;">                                                 
                                                  <?php if($info[$result_key]['perfectpixel'] == true): ?>                                                    
                                                    <p>Perfect pixel : 
                                                      <a target="_blank" class="btn btn-sm btn-primary" href="<?php echo e($result['result']->result); ?>"><?php echo e(round($result['result']->rate,2)); ?>%</a>
                                                    </p>
                                                  <?php endif; ?>
                                                </div>
                                                <form method="post" action="<?php echo e(route('checkfrontendcode_choose_design')); ?>">
                                                  <div class="form-group">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="info_key" value="<?php echo e($info_key); ?>">                                  
                                                    <input type="hidden" name="url" value="<?php echo e($result_key); ?>">
                                                    <label for="select_design">Select design</label>                                
                                                    <select id="select_design" class="form-control" style="width: 100%; vertical-align: middle;" name="design_info" onchange="submitForm(this.form)">
                                                      <option></option>
                                                      <?php $__currentLoopData = $file_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($file[1]); ?>,<?php echo e($file[2]); ?>" 
                                                        <?php if(isset($result['url_design'])): ?> 
                                                        <?php if($result['url_design'] == $file[1]): ?>
                                                        selected
                                                        <?php endif; ?> 
                                                        <?php endif; ?>
                                                        >
                                                        <?php echo e($file[0]); ?>


                                                        <?php if(isset($result['url_design'])): ?> 
                                                        <?php if($result['url_design'] == $file[1]): ?>
                                                        <?php if(isset($result['width'])): ?>
                                                        ( <?php echo e($result['width']); ?>px )
                                                        <?php endif; ?>
                                                        <?php endif; ?> 
                                                        <?php endif; ?>

                                                        </option>
                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                  </select>
                                                </form>                                             
                                              </div>                                                                                     
                                            </div>                                          
                                            <div class="row text-center">                                              
                                              <div class="col-sm border-secondary"  style="width: 230px;">
                                                <?php if(isset($result['url_screenshot'])): ?>
                                                <p>Web</p>                                                
                                                <img class="uploadfile1" width="100%" height="auto" src="<?php echo e($result['url_screenshot']); ?>">
                                                <?php endif; ?>
                                              </div>
                                              <div class="col-sm border-secondary"  style="width: 230px;">
                                                <?php if(isset($result['url_design'])): ?>
                                                <p>Design</p>
                                                <img class="uploadfile1" width="100%" height="auto" src="<?php echo e($result['url_design']); ?>">
                                                <?php endif; ?>
                                              </div>                                              
                                            </div>                                        
                                          </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                  <?php endif; ?>       
                                </ul>                              
                              </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                          
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>        
  </div>
  <div class="col-3">
     <div class="main-content row justify-content-center">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header" style="text-align: center;">
                    <a style="font-size: 1.2rem; text-align: center;">MANAGER DESIGN</a>
                    <button id="btnUploadDesign" type="button" class="btn btn-success float-right">UPLOAD NEW DESIGN</button>                 
                    <form class="form-update-screenshot" action="<?php echo e(route('checkfrontendcode_upload_file')); ?>" method="post" enctype="multipart/form-data">
                      <?php echo csrf_field(); ?> 
                      <input class="fileupload" type="file" accept=".png,.jpeg,.jpg,.psd" name="fileupload[]" style="display:none" onchange="submitForm(this.form)" multiple>
                    </form> 
                  </div>
                  <div class="card-body">
                      <table class="table table-chatwork_translate_v2">
                          <thead>
                              <tr class="text-center">
                                  <th class="text-left">File Name</th>
                                  <th class="text-left">Thumbnail</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                            <?php $__currentLoopData = $file_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td style="max-width:400px;word-wrap: break-word;"><?php echo e($file[0]); ?></td>
                                <td style="max-width:200px;"><img width="100%" height="auto" src="<?php echo e($file[1]); ?>"></td>
                                <td style="max-width:100px;">
                                  <form class="form-update-screenshot" action="<?php echo e(route('checkfrontendcode_delete_design')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="path" value="<?php echo e($file[2]); ?>">
                                    <button class="btn btn-danger" onclick="deleteAlert()">Delete</button>
                                  </form>
                                </td>
                              </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div> 
</div> 
</div>

<div class="loader-div">
  <div class="loader"></div>
</div>


<script type="text/javascript">
  
  $(document).ready(function() {

      $(".validation_form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);        
        form.append('<div id="loadding"><img src="<?php echo e(asset("img/gif/loadding.gif")); ?>" style="width:'+form.height()+'px; display:inline-block;"><p>Checking</p></div>');
        form.children().not("#loadding").hide();
        var num = form.children('button').children('#num').text();

        var url = form.attr('action');
        $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
              console.log(data);
               if(data.code == 1){
                  form.parent().append('<div class="text-left"><strong> '+num+'</strong><br><strong>'+data.errorcount+'<span class="text-danger"> errors</span></strong><br><strong>'+data.warningcount+'<span class="text-warning"> warnings</span></strong><br></div>');
                  form.remove();
               }else{
                  form.children().not("#loadding").show();
                  form.children("#loadding").hide();
               }
           },
           error: function (request, error) {
              location.reload(true);
            },
         });
      });

      $("#btnUploadDesign").click(function(e){
        e.preventDefault();
        $(".fileupload").trigger('click');
      });

      $("#btnUploadSource").click(function(e){
        e.preventDefault();
        $(".sourcecode_upload").trigger('click');
      });



  });

  function loader(form){
        if($('#loader').css('display') == 'none'){
          $(".main-content").css("display","none");
          $("#myModal").css("opacity","0");
          $(".modal-backdrop").remove();
          $("#loader").css("display","block");
          event.preventDefault();
          form.submit();
        }  
      }

  function deleteAlert() {
    var r = confirm("Do you want to delete this?");
    if (r != true) {
       event.preventDefault();
    }else{
      $("#loader").css("display","block");
    }
  }

  function submitForm(form){
    $(".loader-div").show();
    form.submit();
  }

  $( document ).ready(function() { 
      $(".loader-div").hide();
  });
</script>  
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/agltool/domains/agl-tool.jp/public_html/resources/views/tool/check_frontend_code/index.blade.php ENDPATH**/ ?>