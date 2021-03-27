<div class="board-list scroll_list right auto-center">
  <?php if (in_array($this->param->page_type, ['company', 'employee'])): ?>
    <div class="title-table">
        <h1><?php echo ($this->param->page_type == 'company')? '콜':'배정' ?> 내역</h1>
    </div>
  <?php endif; ?>
  <?php require_once _VIEW.'/common/datepicker.php' ?>
  <?php require_once 'callTable.php' ?>
  <?php require_once 'employeeTable.php' ?>
</div>
<?php require_once _VIEW.'common/modal.php' ?>

<script>
    $('.callRow').on('click',function () {
       $(this).closest('table').find('.callRow').removeClass('selected');
       $(this).addClass('selected');
    });
</script>