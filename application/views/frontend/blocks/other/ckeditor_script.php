<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?php if ($error): ?>
    <script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(2, '', 'Неверное расширение файла.');</script>
<?php else: ?>
    <script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(2, '<?= $img_url ?>', '');</script>
<?php endif; ?>





