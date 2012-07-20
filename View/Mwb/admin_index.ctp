
<div class="mwb mwb index">
	<h2><?php echo __d('Mwb', 'Mysql Workbench to CakePHP Schema exporter'); ?></h2>
	<p>
		<?php echo __d('Mwb', 'Simply put your Mwb files in your Config/Schema folder'); ?>
	</p>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __d('Mwb', 'File name'); ?></th>
			<th class="actions"><?php echo __d('Mwb', 'Actions'); ?></th>
		</tr>
		<?php foreach($files as $file): ?>
			<tr>
				<td>
					<?php echo $file['name']; ?>
				</td>
				<td class="actions">
					<?php
					$url = array_merge(
						array('action' => 'generate'),
						explode('/', $file['name'])
					); 
					echo $this->Html->link(__d('Mwb', 'Generate schema'), $url); 
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
