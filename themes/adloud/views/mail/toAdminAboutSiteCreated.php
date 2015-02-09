<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 08.08.14
 * Time: 18:27
 * @var \application\components\ControllerBase $this
 * @var \application\models\Sites $model
 * @var string $title
 * @var string $id
 */
?>

<center><h3><?php echo $title; ?></h3></center>

<table>

    <thead>

        <th>
            <td colspan="4">Информация о сайте</td>
        </th>

    </thead>

    <tbody>

        <tr>
            <td>ID</td>
            <td><?php echo $id; ?></td>
        </tr>

        <tr>
            <td>Адрес сайта</td>
            <td><?php echo $model->url; ?></td>
        </tr>

        <tr>
            <td>ID пользователя</td>
            <td><?php echo $model->getUserId(); ?></td>
        </tr>

        <tr>
            <td>Имя пользователя</td>
            <td><?php echo $model->getUserEmail(); ?></td>
        </tr>

    </tbody>

</table>