<div class="modal fade" id="<?php echo isset($id) ? $id : 'modal-image-upload-crop'; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo isset($title) ? $title : 'Изображение'; ?></h4>
            </div>
            <div class="modal-body" id="<?php echo isset($imageContainerId) ? $imageContainerId : 'crop-image-container-id'; ?>" >
                <p><img id="<?php echo isset($imageId) ? $imageId : 'crop-image-id'; ?>" src="<?php echo isset($imageSrc) ? $imageSrc : '#';  ?>" /></p>
            </div>
            <div class="modal-footer">
                <button id="submit-crop-image" type="button" class="btn btn-primary">Изменить</button>
            </div>
        </div>
    </div>
</div>
<div id="ads-model-window-hidden-id">
    <?php
        echo CHtml::hiddenField('Crop[height]', isset($minHeight) ? $minHeight : 0, array('class'=>'image-height'));
        echo CHtml::hiddenField('Crop[width]', isset($minWidth) ? $minWidth : 0, array('class'=>'image-width'));
        echo CHtml::hiddenField('Crop[x1]', isset($minWidth) ? $minWidth : 0, array('class'=>'image-x1'));
        echo CHtml::hiddenField('Crop[x2]', null, array('class'=>'image-x2'));
        echo CHtml::hiddenField('Crop[y1]', isset($minWidth) ? $minWidth : 0, array('class'=>'image-y1'));
        echo CHtml::hiddenField('Crop[y2]', null, array('class'=>'image-y2'));
        echo CHtml::hiddenField('Crop[image][height]', isset($height) ? $height : 0);
        echo CHtml::hiddenField('Crop[image][width]', isset($width) ? $width : 0);
    ?>
</div>