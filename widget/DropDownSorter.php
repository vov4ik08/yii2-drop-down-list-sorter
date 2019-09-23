<?php

namespace i4erkasov\dropdownlistsorter\widget;

use Yii;
use yii\helpers\Html;
use yii\widgets\LinkSorter;

/**
 *
 * LinkSorter class extension for presentation
 * sort options as DropDownList.
 *
 * Class DropDownSorter
 * @package frontend\components\widgets
 */
class DropDownSorter extends LinkSorter
{
    /**
     * @var string name for DropDownList
     */
    public $sortName = 'sort-dropdown';

    /**
     * @inheritDoc
     * @throws \yii\base\InvalidConfigException
     */
    protected function renderSortLinks()
    {
        $attributes   = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links        = $this->getArrayUrl($attributes);
        $currentParam = Yii::$app->request->get($this->sort->sortParam);

        return Html::dropDownList($this->sortName, $this->sort->createUrl($currentParam), $links, $this->options);
    }

    /**
     * @param $attributes
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getArrayUrl($attributes): array
    {
        foreach ($attributes as $name) {
            $attribute = $this->sort->attributes[$name];

            if ($attribute['asc']) {
                $links[$this->sort->createUrl($name)] = ($attribute['label']['asc']) ?: $name;
            }

            if ($attribute['desc']) {
                $links[$this->sort->createUrl('-' . $name)] = $attribute['label']['desc'];
            }
        }

        return (isset($links)) ? $links : [];
    }
}