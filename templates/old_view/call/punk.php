<div class="board-list scroll_list right auto-center">
  <?php if (in_array($this->param->page_type, ['company', 'employee'])): ?>
      <h1>펑크 내역</h1>
  <?php else: ?>
    <?php require_once _VIEW.'/common/datepicker.php' ?>
  <?php endif; ?>
  <?php $type = 'punk'; require 'callTable.php' ?>
</div>
<?php require_once _VIEW.'common/modal.php' ?>