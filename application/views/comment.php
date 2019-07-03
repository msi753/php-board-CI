<?php if(is_array($LOOP)):?>
    <?php foreach($LOOP as $row):?>
        <li class="list-group-item">
            <div class="float-right">
                <a href="#" class="btn btn-danger" data-sno="<?=$row['NO']?>">삭제</a>
            </div>
            <?=$row['WRITER']?>
            <?=$row['DATE']?>
            <div class="clear"></div>
            <?=$row['CONTENTS']?>
        </li>
    <?php endforeach;?>
<?php endif;?>
