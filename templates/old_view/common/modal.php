<style>
    form.search-form {
        margin-top: 20px;
        display: block;
    }

    .wrap .search {
        position: relative
    }

    .wrap .search .searchTerm {
        display: inline;
        float: left;
        width: 400px;
        height: 44px;
        padding: 0;
        border: 3px solid #66b8fe;
        border-right: none;
    }

    .wrap .search .searchTerm:focus {
        color: #66b8fe;
    }

    .wrap .search .searchButton {
        display: inline;
        float: left;
        width: 70px;
        height: 50px;
        padding: 0;
        border: 1px solid #66b8fe;
        background: #66b8fe;
        color: #fff;
        cursor: pointer;
        text-align: center;
        font-size: 20px;
    }
</style>

<!-- Delete Modal -->
<div id="modalDelete" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l">삭제 사유를 입력하세요</div>
        <form action="" method="post" id="formDelete">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="table" id="deleteTable">
            <input type="hidden" name="id" id="deleteID">
            <textarea name="deleteDetail"></textarea>
            <button type="button" class="btn btn-danger" id="btnDelete">삭제</button>
        </form>
    </div>
</div>
<!-- Join Cancel Modal -->
<div id="modalJoinCancel" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l">가입 삭제 사유를 입력하세요</div>
        <form action="" method="post" id="formJoinCancel">
            <input type="hidden" name="action" value="joinDelete">
            <input type="hidden" name="table" id="joinDeleteTable">
            <input type="hidden" name="id" id="joinDeleteID">
            <textarea name="detail"></textarea>
            <button type="button" class="btn btn-danger" id="btnJoinCancel">삭제</button>
        </form>
    </div>
</div>
<!-- Call Cancel Modal -->
<div id="modalCallCancel" class="modal">
    <div class="modal-content">
        <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        <div class="modal-box al_l">취소 사유를 입력하세요</div>
        <form id="formCallCancel" action="" method="post">
            <input type="hidden" name="action" value="callCancel" >
            <input type="hidden" name="callID" id="callCancelID" >
            <textarea name="detail"
                      id="detail" <?php if ($this->param->page_type == 'ceo') echo "size='200'" ?> > </textarea>
            <button id="btnCallCancel" type="button"
                    class="btn <?php if ($this->param->page_type == 'ceo') echo "btn-mobile" ?> btn-insert">콜 취소
            </button>
        </form>
    </div>
</div>
<!-- Assign Cancel Modal -->
<div id="modalAssignCancel" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l flex-assign-cancel">
            <form action="" method="post">
                <input type="hidden" name="action" value="assignCancel">
                <input type="hidden" name="callID">
                <input type="hidden" name="employeeID">
                <input class="btn btn-insert" type="button" value="배정취소" id="btnAssignCancel">
            </form>
            <input class="btn btn-insert" type="button" value="펑크" id="btnPunk">
            
        </div>
        <form class="search-form" action="" method="post" id="formPunk">
            <input type="hidden" name="action" value="punk">
            <input type="hidden" name="callID">
            <input type="hidden" name="employeeID">
            <div class="wrap" style="display: none;" id="wrapPunk">
                <div class="search">
                    <input type="text" class="searchTerm" name="detail" placeholder="펑크 사유를 입력하세요">
                    <input type="button" class="searchButton" id="btnSubmitPunk" value="펑크"></input>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="snackbar">으뜸파출-메세지가 입력되지 않았습니다.</div>

<script>
    $(document).ready(function () {
        $('#modalAssignCancel #btnPunk').on('click', function () {
            console.log('show me');
            $('#modalAssignCancel #wrapPunk').show();
        });
        $('#modalAssignCancel #btnAssignCancel').on('click', function () {
            let id = $('#modalAssignCancel input[name=callID]').val();
            console.log('id' + id);
            console.log(id);
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: {action: 'assignCancel', callID: id},
                dataType: "text",
                success: function (data) {
                    alert('배정을 취소했습니다.');
                    $('.modal').hide();
                    $('tr.callRow#' + id).find('td.assignedEmployee').html(null);
                }
            });
        });
        $('#modalAssignCancel #btnSubmitPunk').on('click',function () {
            let callID = $('#modalAssignCancel #formPunk input[name=callID]').val();
            let employeeID = $('#modalAssignCancel #formPunk input[name=employeeID]').val();
            let detail = $('#modalAssignCancel #formPunk input[name=detail]').val();
            console.log(callID);
            console.log(employeeID);
            $.ajax({
                type: "POST",
                method: "POST",
                url: ajaxURL,
                data: {action: 'punk', callID: callID, employeeID:employeeID, detail:detail},
                dataType: "text",
                success: function (data) {
                    // console.log(JSON.parse(data));
                    alert('펑크 처리가 완료되었습니다.\n새로운 인력을 배정 해 주세요.');
                    $('.modal').hide();
                    $('tr.callRow#' + callID).find('td.assignedEmployee').html(null);
                }
            });
        });
    });
</script>

<!-- Join Cancel Modal -->
<div id="modalGetMoney" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l">수금자 이름을 입력하세요</div>
        <form action="" method="post" id="formGetMoney">
            <input type="hidden" name="table" id="inputGetMoneyTable">
            <input type="hidden" name="value" id="inputGetMoneyValue">
            <input type="hidden" name="id" id="inputGetMoneyID">
            <textarea name="receiver" id="inputGetMoneyReceiver"></textarea>
            <button type="button" class="btn btn-submit" id="btnGetMoney">수금완료</button>
        </form>
    </div>
</div>
<!-- Fix Cancel Modal -->
<div id="modalFixCancel" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <h1 class="detail">취소사유</h1>
        <form id="fixCancelForm" action="" method="post">
            <input name="action" type="hidden" value="fixCancel">
            <input name="fixID" type="hidden" id="fixCancelID">
            <input type="date" name="date">
            <textarea name="detail" id="detail" size="200"></textarea>
            <input id="fixCancelBtn" class="btn btn-insert" type="submit" value="콜 취소">
        </form>
    </div>
</div>
<!-- Join Update Modal -->
<div id="modalJoinUpdate" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l">가입 내역 수정</div>
        <form action="" method="post">
            <input type="hidden" name="action" value="join_update">
            <input id="updateID" type="hidden" name="joinID">
            <table>
                <colgroup>
                    <col width="25%">
                    <col width="75%">
                </colgroup>
                <tr>
                    <td class="td-title">금액</td>
                    <td><input type="number" id="updatePrice" name="price" min="0"></td>
                </tr>
                <tr>
                    <td class="td-title">비고</td>
                    <td><textarea id="updateDetail" name="joinDetail"></textarea></td>
                </tr>
            </table>
            <div class="al_r">
                <input class="btn btn-submit" type="submit" value="수정">
            </div>
        </form>
    </div>
</div>
<!-- Pay Charged Call Modal -->
<div id="modalPayChargedCall" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l">
            <form>
                <input type="text" style="height: 50px;" id="pay-info" value="국민은행 477002-04-040107">
                <input id="copyBtn" class="btn btn-insert" type="submit" value="계좌번호 복사하기">
            </form>
        </div>
    </div>
</div>
<!-- Call Update Modal -->
<div id="modalCallUpdate" class="modal">
    <div class="modal-content">
        <div class="modal-box al_r">
            <button type="button" class="btn btn-close-modal"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-box al_l">콜 정보 수정</div>
        <form id="formUpdateCall" action="" method="post">
            <input type="hidden" name="action" value="update_call">
            <input id="callID" type="hidden" name="callID">
            <table>
                <colgroup>
                    <col width="25%">
                    <col width="75%">
                </colgroup>
                <tr>
                    <td class="td-title">콜비</td>
                    <td><input type="number" id="price" name="price" min="0"></td>
                </tr>
                <tr>
                    <td class="td-title">요청사항</td>
                    <td><textarea id="detail" name="detail"></textarea></td>
                </tr>
            </table>
            <div class="al_r">
                <input class="btn btn-submit" type="submit" value="수정">
            </div>
        </form>
    </div>
</div>
<script>

</script>