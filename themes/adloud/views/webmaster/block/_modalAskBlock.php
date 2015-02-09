<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 28.07.14
 * Time: 19:33
 * @var array $formats
 */
?>

<?php $i = 0; ?>
<?php foreach($formats as $id=>$name): ?>
    <div class="form-group ">
        <input
            type="radio"
            class="block_type_selector cursor-pointer"
            name="blockType"
            value="<?php echo $id; ?>"
            <?php if($i == 0) echo 'checked="checked"'; ?>
        />
        <label class="adloud_label block_type_selector cursor-pointer"><?php echo $name; ?></label>
        <br />

    </div>
    <?php $i++; ?>
<?php endforeach; ?>