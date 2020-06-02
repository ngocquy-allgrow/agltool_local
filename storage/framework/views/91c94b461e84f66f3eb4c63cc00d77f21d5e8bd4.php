

<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;">
                    <a class="btn btn-secondary float-left" href="<?php echo e(url('/')); ?>">BACK</a>
                    <a href="<?php echo e(route('chatwork_admin_index')); ?>" style="font-size: 1.2rem;">CHATWORK ADMIN</a>
                    <br>
                    <br>
                	<select name="room_id" class="form-control form-control-lg" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    	<?php $__currentLoopData = $chatworkRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chatworkRoom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    		<option <?php if($room_id == $chatworkRoom->room_id): ?> selected <?php endif; ?> value="<?php echo e(route('chatwork_admin_detail',['room_id' => $chatworkRoom->room_id])); ?>"><?php echo e($chatworkRoom->room_id); ?> : <?php echo e($chatworkRoom->name); ?></option>
                    	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>     
                </div>
                <div class="card-body">
                	<table class="table">
                		<tr>
                			<th width="15%">Account Name</th>
                			<th>Body</th>
                            <th width="15%"></th>
                		</tr>
                		<?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                		<?php if(isset($data->token)): ?>              		
                		<tr> 
            				<td><?php echo e($data->account->name); ?></td>
                			<td>
                				<form id="form-<?php echo e($data->message_id); ?>" action="" method="POST">
                				<?php echo csrf_field(); ?>                				 
                				<textarea class="d-inline md-textarea form-control" name="body"
                                <?php if(strpos($data->body, '[hr]') !== false): ?>  <?php else: ?> style="border-color: red" <?php endif; ?>
                                ><?php echo e($data->body); ?></textarea>
                				<input type="hidden" name="token" value="<?php echo e($data->token); ?>">
                				<input type="hidden" name="room_id" value="<?php echo e($room_id); ?>">
                				<input type="hidden" name="message_id" value="<?php echo e($data->message_id); ?>">                				
                				</form>                                                  				
                			</td>
                            <td>
                                <button class="btn btn-success edit" onclick="edit('form-<?php echo e($data->message_id); ?>')">Edit</button>
                                <button class="btn btn-danger delete" onclick="del('form-<?php echo e($data->message_id); ?>')">Delete</button>
                            </td>
                		</tr> 
                		<?php endif; ?>               		
                		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                	</table>                    
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    	$("textarea").each(function(textarea) {
		    $(this).height( $(this)[0].scrollHeight );
		});

        function edit(form){
            var form = $("#"+form);
            form.attr('action', "<?php echo e(route('chatwork_admin_edit_message')); ?>");
            form.submit();
        }

        function del(form){
            var r = confirm("Do you want to delete this message");
            if (r == true) {
                var form = $("#"+form);
                form.attr('action', "<?php echo e(route('chatwork_admin_del_message')); ?>");
                form.submit();
            }
        }
    </script>    
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/agltool/domains/agl-tool.jp/public_html/resources/views/tool/chatwork_translate_v3/admin.blade.php ENDPATH**/ ?>