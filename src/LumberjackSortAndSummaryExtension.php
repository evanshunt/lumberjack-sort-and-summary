<?php

namespace EvansHunt\LumberjackSortAndSummary;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Lumberjack\Model\Lumberjack;
use SilverStripe\ORM\DataList;

class LumberjackSortAndSummaryExtension extends Lumberjack
{

    // this will use $summary_fields and $default_sort of the provided class
    public function getLumberjackPagesForGridField($excluded = []): DataList
    {
        $childClasses = $this->getChildClassesOtherThanSiteTree();

        if (count($childClasses) === 1) {
            $className = $childClasses[0];
            return $className::get()->filter(
                [
                    'ParentID' => $this->getOwner()->ID,
                    'ClassName' => $excluded,
                ]
            );
        }
        return parent::getLumberjackPagesForGridField($excluded);
    }

    // this will change the tab title
    public function getLumberJackTitle(): string
    {
        $childClasses = $this->getChildClassesOtherThanSiteTree();

        if (count($childClasses) === 1) {
            return Config::inst()->get($childClasses[0], 'plural_name');
        }
        return parent::getLumberjackTitle();
    }

    private function getChildClassesOtherThanSiteTree(): array
    {
        $childClasses = Config::inst()->get(get_class($this->owner), 'allowed_children');
        return array_values(array_filter($childClasses, function ($className) {
            return $className !== SiteTree::class;
        }));
    }

}
