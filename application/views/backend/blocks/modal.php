<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div id="<?= $modal_id ?>" class="modal hide fade">

  <div class="modal-header">
    <a class="close" data-dismiss="modal" >&times;</a>
    <h3>Автосервисы</h3>
  </div>
  <div class="modal-body">
    <?= $content; ?>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" >Закрыть</a>
  </div>
</div>