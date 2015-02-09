<?php


namespace models\dataSource;
use models\dataSource\DataSourceLayer;

class CategoryDataSource extends DataSourceLayer
{

    public function tableName()
    {
        return 'categories';
    }

    public function getList(){
        $sql = 'SELECT
                    "id",
                    "description",
                    "min_click_price" AS "minimumClickPrice"
                FROM "categories" WHERE "active" = 1';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryNames(array $ids){

        $placeholders = rtrim(str_repeat('?, ', count($ids)), ', ');
        $sql = 'SELECT
                    "id",
                    "description",
                    "min_click_price" AS "minimumClickPrice"
                FROM "categories" WHERE "active" = 1 AND "id" IN ('.$placeholders.')';
        $statement = $this->pdoPersistent->prepare($sql);
        $statement->execute($ids);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

}