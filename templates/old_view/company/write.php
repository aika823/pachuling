<?php 
    $companyData = $this->companyData;
    $ceo_List = $this->get_ceo_list();
    $address_List = $this->get_address_list();
    $businessType_List = $this->get_business_type_list();
?>
<div class="board-write auto-center">
    <div class="title-table">
        <h1 class="title-main">
          <?php
            echo "거래처정보";
            if (isset ($companyData)) echo " - " . $companyData['companyName'] . "(" . $companyData['actCondition'] . ")";
          ?>
        </h1>
    </div>
    <div class="form-style-1">
        <form id="formInsertCompany" action="" method="post">
            <fieldset>
                <input type="hidden" name="action"
                       value="<?php echo ($this->param->action == 'write') ? 'insert' : 'update' ?>">
                <input type="hidden" name="companyID" value="<?php echo $companyData['companyID'] ?>">
                <input type="hidden" name="ceoID" value="<?php echo $this->ceoData['ceoID'] ?>">
                <div class="table">
                    <div class="tr">
                        <div class="td td-4">
                            <label for="">상호명</label>
                            <input type="text" name="companyName" size="20" required autofocus
                                   value="<?php echo $companyData['companyName']; ?>">
                        </div>
                        <div class="td td-4">
                            <label for="">대표자명</label>
                            <input type="text" list="ceoList" name="ceoName" size="20" required
                                   value="<?php echo $this->ceoData['ceoName']; ?>">
                            <datalist id="ceoList" class="input-field">
                              <?php foreach ($this->ceo_List as $data): ?>
                                  <option value="<?php echo $data['ceoName']?>"></option>
                              <?php endforeach ?>
                            </datalist>
                        </div>

                        <div class="td td-4">
                            <label for="">업종</label>
                            <input type="text" list="businessTypeList" name="businessType" size="20"
                                   required value="<?php echo $companyData['businessType']; ?>">
                            <datalist id="businessTypeList" class="input-field">
                              <?php foreach ($this->businessType_List as $data): ?>
                                  <option value="<?php echo $data['businessType']?>"></option>
                              <?php endforeach ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="tr">
                        <div id="companyNameDuplicate">이름을 입력 해 주세요</div>
                    </div>
                    <div class="tr">
                        <div class="td td-4">
                            <label for="">거래처전화</label>
                            <input type="text" name="companyPhoneNumber" size="20" required
                                   value="<?php echo $companyData['companyPhoneNumber']; ?>">
                        </div>
                        <div class="td td-4">
                            <label for="">사장전화</label>
                            <input type="text" name="ceoPhoneNumber" size="20" required
                                   value="<?php echo $this->ceoData['ceoPhoneNumber']; ?>">
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td td-4">
                            <label for="">간단주소</label>
                            <input type="text" list="addressList" name="address"
                                   value="<?php echo $companyData['address']; ?>">
                            <datalist id="addressList">
                              <?php foreach ($this->address_List as $data): ?>
                                  <option value="<?php echo $data['address']?>"></option>
                              <?php endforeach ?>
                            </datalist>
                        </div>
                        <div class="td td-6">
                            <label for="">상세주소</label>
                            <input type="text" name="detailAddress" size="50" style="width: 680px;"
                                   value="<?php echo $companyData['detailAddress']; ?>">
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td td-4">
                            <label for="">점수</label>
                            <input type="number" name="grade" min="0" max="100" required
                                   value="<?php echo $companyData['grade']; ?>">
                        </div>
                    </div>
                    <div class="tr">
                        <div class="td td-4">
                            <label for="">비고</label>
                            <textarea class="textarea-detail"
                                      name="detail"><?php echo $this->get_detail($companyData, 'company'); ?></textarea>
                        </div>
                      <?php if ($companyData['deleted'] == 1) : ?>
                          <div class="td td-4">
                              <label for="">삭제비고</label>
                              <textarea class="textarea-detail"
                                        name="deleteDetail"><?php echo $companyData['deleteDetail']; ?></textarea>
                          </div>
                      <?php endif; ?>
                    </div>
                  <?php if (($this->param->action == 'view') && (sizeof($this->blackList) > 0)): ?>
                      <div class="tr">
                          <div class="td td-9">
                              <label for="">블랙</label>
                              <table>
                                  <thead>
                                  <tr>
                                      <th width="150">성명</th>
                                      <th width="150">종류</th>
                                      <th>사유</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php foreach ($this->blackList as $data) {?>
                                      <tr>
                                          <td>
                                              <?php echo $this->employeeName($data['employeeID']) ?>
                                          </td>
                                          <td>
                                              <?php echo ($data['ceoReg'] == 1) ? '오지마세요' : '안가요'; ?>
                                          </td>
                                          <td class="<?php echo $data['detail'] ? 'al_l' : 'al_c'?>">
                                              <?php echo $data['detail'] ? $data['detail'] : '-'; ?>
                                          </td>
                                      </tr>
                                  <?php } ?>
                                  <tr>

                                  </tr>
                                  </tbody>
                              </table>

                          </div>
                      </div>
                  <?php endif; ?>
                </div>
              <?php if ($this->param->action == 'write') : ?>
                <?php require_once 'companyJoinForm.php' ?>
              <?php endif; ?>
            </fieldset>
            <div class="btn-group al_r">
                <a class="btn btn-default" href="<?php echo $this->param->get_page ?>">뒤로 가기</a>
                <button class="btn btn-<?php echo ($this->param->action == 'write') ? 'insert' : 'submit' ?>"
                        type="submit"><?php echo ($this->param->action == 'write') ? '추가' : '수정' ?></button>
            </div>
        </form>
    </div>
</div>