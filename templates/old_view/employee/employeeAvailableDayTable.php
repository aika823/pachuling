<table>
  <tr>
    <th></th>
    <th>월</th>
    <th>화</th>
    <th>수</th>
    <th>목</th>
    <th>금</th>
    <th>토</th>
    <th>일</th>
  </tr>
  <tr>
    <th>오전</th>
    <td><input class="day bn mon" type="checkbox"<?php echo $this->getDay('mon', '오전') ?> ></td>
    <td><input class="day bn tue" type="checkbox"<?php echo $this->getDay('tue', '오전') ?> ></td>
    <td><input class="day bn wed" type="checkbox"<?php echo $this->getDay('wed', '오전') ?> ></td>
    <td><input class="day bn thu" type="checkbox"<?php echo $this->getDay('thu', '오전') ?> ></td>
    <td><input class="day bn fri" type="checkbox"<?php echo $this->getDay('fri', '오전') ?> ></td>
    <td><input class="day bn sat" type="checkbox"<?php echo $this->getDay('sat', '오전') ?> ></td>
    <td><input class="day bn sun" type="checkbox"<?php echo $this->getDay('sun', '오전') ?> ></td>
  </tr>
  <tr>
    <th>오후</th>
    <td><input class="day an mon" type="checkbox"<?php echo $this->getDay('mon', '오후') ?> ></td>
    <td><input class="day an tue" type="checkbox"<?php echo $this->getDay('tue', '오후') ?> ></td>
    <td><input class="day an wed" type="checkbox"<?php echo $this->getDay('wed', '오후') ?> ></td>
    <td><input class="day an thu" type="checkbox"<?php echo $this->getDay('thu', '오후') ?> ></td>
    <td><input class="day an fri" type="checkbox"<?php echo $this->getDay('fri', '오후') ?> ></td>
    <td><input class="day an sat" type="checkbox"<?php echo $this->getDay('sat', '오후') ?> ></td>
    <td><input class="day an sun" type="checkbox"<?php echo $this->getDay('sun', '오후') ?> ></td>
  </tr>
  <tr>
    <th>종일</th>
    <td><input class="day ad mon" type="checkbox"<?php echo $this->getDay('mon', '종일') ?> ></td>
    <td><input class="day ad tue" type="checkbox"<?php echo $this->getDay('tue', '종일') ?> ></td>
    <td><input class="day ad wed" type="checkbox"<?php echo $this->getDay('wed', '종일') ?> ></td>
    <td><input class="day ad thu" type="checkbox"<?php echo $this->getDay('thu', '종일') ?> ></td>
    <td><input class="day ad fri" type="checkbox"<?php echo $this->getDay('fri', '종일') ?> ></td>
    <td><input class="day ad sat" type="checkbox"<?php echo $this->getDay('sat', '종일') ?> ></td>
    <td><input class="day ad sun" type="checkbox"<?php echo $this->getDay('sun', '종일') ?> ></td>
  </tr>
</table>
<input type="hidden" name="mon" value="<?php echo $this->getDay('mon')?>">
<input type="hidden" name="tue" value="<?php echo $this->getDay('tue')?>">
<input type="hidden" name="wed" value="<?php echo $this->getDay('wed')?>">
<input type="hidden" name="thu" value="<?php echo $this->getDay('thu')?>">
<input type="hidden" name="fri" value="<?php echo $this->getDay('fri')?>">
<input type="hidden" name="sat" value="<?php echo $this->getDay('sat')?>">
<input type="hidden" name="sun" value="<?php echo $this->getDay('sun')?>">