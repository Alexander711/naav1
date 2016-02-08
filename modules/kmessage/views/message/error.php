<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<ul id="messages">
	<?php foreach ($messages as $message) { ?>
		<li class="error">
			<p><?php echo $message ?></p>
		</li>
	<?php } ?>
</ul>