<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.06.14
 * Time: 14:37
 * @var GeoController $this
 * @var CampaignForm $model
 * @var string $campaignId
 */
?>

<?php foreach($model->countries as $country): ?>
<li>
    <p data-url="<?php echo Yii::app()->createUrl('geo/getRegions', ['campaignId' => $campaignId, 'countryId' => $country->id]); ?>" class="adloud_label">
        <b class="open_region caret"></b>
        <label class="checkbox region_check<?php echo $country->isChecked ? ' checked' : ''; ?>" for="check_ru">
            <input type="checkbox" data-toggle="checkbox" name="CampaignForm[country][<?php echo $country->id; ?>]"<?php echo $country->isChecked ? ' checked="checked"' : ''; ?> />
        </label>
        <span class="open_region"><?php echo $country->name; ?></span>
    </p>
    <ul class="region_list">

    </ul>
</li>
<?php endforeach; ?>