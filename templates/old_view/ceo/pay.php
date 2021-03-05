<div class="mobile_view">
    <form class="list-form" method="post">
        <select class="paid-filter" name="year" id="paid-year" form="listForm" required>
          <?php foreach ($this->yearList as $value): ?>
              <option class="year" value="<?php echo $value ?>"><?php echo $value.'년' ?></option>
          <?php endforeach; ?>
        </select>
        <select class="paid-filter" name="month" id="paid-month" form="listForm">
          <?php foreach ($this->monthList as $value): ?>
              <option class="year" value="<?php echo $value ?>"><?php echo $value.'월' ?></option>
          <?php endforeach; ?>
        </select>
    </form>
    <h1 class="total-price link" ></h1>
    <table class="call-list">
        <colgroup>
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <col width="20%">
        </colgroup>
        <thead>
        <tr>
            <th class="al_c link" onclick="sortTable('callList',0)">근무일</th>
            <th class="al_c link" onclick="sortTable('callList',1)">근무시간</th>
            <th class="al_c link" onclick="sortTable('callList',2)">직종</th>
            <th class="al_c link" onclick="sortTable('callList',3)">콜비</th>
            <th class="al_c link" onclick="sortTable('callList',4)">배정</th>
        </tr>
        </thead>
        <tbody id="paid-call-list-body"></tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        let company_id = $('.user-profile').attr('id');
        let year = $('#paid-year').val();
        let month = $('#paid-month').val();
        let type = 'paid';
        fetch_call_table(company_id, year, month, type);
    });
</script>