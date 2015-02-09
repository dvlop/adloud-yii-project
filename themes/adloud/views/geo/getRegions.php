<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.06.14
 * Time: 14:37
 * @var GeoController $this
 * @var CampaignForm $model
 * @var array $countriesId
 * @var string $campaignId
 */
?>

<?php foreach($model->getRegions($countriesId) as $region): ?>
    <li>
        <p data-url="<?php echo Yii::app()->createUrl('geo/getCities', ['campaignId' => $campaignId, 'regionId' => $region->id]); ?>" class="adloud_label">
            <b class="open_region caret"></b>
            <label class="checkbox region_check<?php echo $region->isChecked ? ' checked' : ''; ?>" for="check_ru">
                <input type="checkbox" data-toggle="checkbox" name="CampaignForm[region][<?php echo $region->id; ?>]"<?php echo $region->isChecked ? ' checked="checked"' : ''; ?> />
            </label>
            <span class="open_region"><?php echo $region->name; ?></span>
        </p>
        <ul class="region_list">

        </ul>
    </li>
<?php endforeach; ?>