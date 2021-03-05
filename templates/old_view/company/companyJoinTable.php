<div class="board-write auto-center">
    <div class="title-table">
        <h1>가입 내역</h1>
    </div>
    <table id="companyJoinTable" width="100%">
        <colgroup>
            <col width="5%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="15%">
            <col width="35%">
            <col width="10%">
        </colgroup>
        <thead>
        <tr class="<?php echo ($this->companyData['activated']) ? 'activated-header' : null ?>">
            <th class="link">#</th>
            <th class="link">가입구분</th>
            <th class="link">가입금액</th>
            <th class="link">가입 시작일</th>
            <th class="link">가입 만기일</th>
            <th class="link">비고</th>
            <th class="link">삭제</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->joinList as $key => $data): ?>
            <tr class="tr-company <?php echo $this->joinColor($data, 'company'); ?>"
                id="<?php echo $data['join_companyID'] ?>">
                <td class="al_c link update join_id"><?php echo $data['join_companyID'] ?></td>
                <td class="al_c"><?php echo $this->get_joinType($data); ?></td>
                <td class="al_c link update join_price"><?php echo $this->get_joinPrice($data); ?></td>
                <td class="al_c"><?php echo $data['startDate'] ?></td>
                <td class="al_c"><?php echo $this->get_endDate($data, 'company'); ?></td>
                <td class="al_l link update join_detail"><?php echo $this->get_joinDetail($data); ?></td>
                <td class="al_c"><?php echo $this->get_join_delete_btn($data, 'company'); ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<!--<script>-->
<!--    $(document).ready(function () {-->
<!--        $('tr.tr-company.activated').last().addClass('last-child');-->
<!--    });-->
<!--</script>-->