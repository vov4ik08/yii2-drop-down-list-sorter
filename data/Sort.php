<?php

namespace frontend\components\data;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\Request;

/**
 * Extension Sort class for working with DropDownSorter widget
 *
 * Class Sort
 * @package frontend\components\data
 */
class Sort extends \yii\data\Sort
{
    /**
     * @var array to exclude from request parameters
     */
    public $unsetParams = [];

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if ($this->defaultOrder === null) {
            throw new InvalidConfigException('The "defaultOrder" property must be set.');
        }

        $attributes = [];

        foreach ($this->attributes as $name => $attribute) {

            if (!is_array($attribute)) {
                $attributes[$attribute] = [
                    'asc'  => [$attribute => SORT_ASC],
                    'desc' => [$attribute => SORT_DESC],
                ];
            } else {
                $attributes[$name] = $attribute;
            }
        }

        $this->attributes = $attributes;
    }

    /**
     * @inheritDoc
     */
    public function createUrl($attribute, $absolute = false): string
    {
        if (($params = $this->params) === null) {
            $request = Yii::$app->getRequest();
            $params  = $request instanceof Request ? $request->getQueryParams() : [];
        }

        if ($this->unsetParams) {
            foreach ($this->unsetParams as $param) {
                unset($params[$param]);
            }
        }

        $params[$this->sortParam] = $attribute;
        array_push($params, $this->route === null ? Yii::$app->controller->getRoute() : $this->route);
        $urlManager = $this->urlManager === null ? Yii::$app->getUrlManager() : $this->urlManager;

        if ($absolute) {
            return $urlManager->createAbsoluteUrl($params);
        }

        return $urlManager->createUrl($params);
    }

    /**
     * @inheritDoc
     */
    public function getOrders($recalculate = false)
    {
        $attributeOrders = $this->getAttributeOrders($recalculate);
        $orders          = [];

        foreach ($attributeOrders as $attribute => $direction) {
            $definition = $this->attributes[$attribute];

            if ($direction === SORT_ASC && $definition['asc']) {
                $columns = $definition['asc'];
            } elseif ($direction === SORT_DESC && $definition['desc']) {
                $columns = $definition['desc'];
            } else {
                $this->setAttributeOrders($this->defaultOrder);

                return $this->getOrders();
            }

            if (is_array($columns) || $columns instanceof \Traversable) {
                foreach ($columns as $name => $dir) {
                    $orders[$name] = $dir;
                }
            } else {
                $orders[] = $columns;
            }
        }

        return $orders;
    }
}