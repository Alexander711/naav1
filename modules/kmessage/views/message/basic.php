<ul id="messages">
	<?php foreach ($messages as $message) { ?>
		<li class="alert-message <?php echo $message->type ?>">
			<p><?php echo $message->text ?></p>
		</li>
	<?php } ?>
</ul>
