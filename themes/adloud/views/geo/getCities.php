<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 17.06.14
 * Time: 14:37
 * @var GeoController $this
 * @var CampaignForm $model
 * @var array $regionsId
 * @var string $campaignId
 */
?>

<?php foreach($model->getCities($regionsId) as $city): ?>
    <li>
        <p class="adloud_label">
            <b class="open_region caret"></b>
            <label class="checkbox region_check<?php echo $city->isChecked ? ' checked' : ''; ?>" for="check_ru">
                <input type="checkbox" data-toggle="checkbox" name="CampaignForm[city][<?php echo $city->id; ?>]"<?php echo $city->isChecked ? ' checked="checked"' : ''; ?> />
            </label>
            <span class="open_region"><?php echo $city->name; ?></span>
        </p>
    </li>
<?php endforeach; ?>