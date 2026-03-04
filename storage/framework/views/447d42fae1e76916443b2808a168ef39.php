		<a class="clear"><?php echo e(__('New Conversation(s).')); ?></a>
		<?php if(count($datas) > 0): ?>
		<a id="conv-notf-clear" data-href="<?php echo e(route('conv-notf-clear')); ?>" class="clear" href="javascript:;">
			<?php echo e(__('Clear All')); ?>

		</a>
		<ul>
		<?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<li>
				<a href="<?php echo e(route('admin-message-show',$data->conversation_id)); ?>"> <i class="fas fa-envelope"></i> <?php echo e(__('You Have a New Message.')); ?></a>
			</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

		</ul>

		<?php else: ?> 

		<a class="clear" href="javascript:;">
			<?php echo e(__('No New Notifications.')); ?>

		</a>

		<?php endif; ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/notification/message.blade.php ENDPATH**/ ?>