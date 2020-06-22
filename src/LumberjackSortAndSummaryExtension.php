<?php

namespace EvansHunt\LumberjackSortAndSummary;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Lumberjack\Model\Lumberjack;

class LumberjackSortAndSummaryExtension extends Lumberjack
{
    
    // this will use $summary_fields and $default_sort of the provided class
    public function getLumberjackPagesForGridField($excluded = [])
    {
        $childClasses = $this->getChildClassesOtherThanSiteTree();

        if (count($childClasses) === 1) {
            $className = $childClasses[0];
            return $className::get()->filter(
                [
                    'ParentID' => $this->owner->ID,
                    'ClassName' => $excluded,
                ]
            );
        }
        return parent::getLumberjackPagesForGridField();
    }

    // this will change the tab title
    public function getLumberJackTitle()
    {
        $childClasses = $this->getChildClassesOtherThanSiteTree();

        if (count($childClasses) === 1) {
            return Config::inst()->get($childClasses[0], 'plural_name');
        }
        return parent::getLumberjackTitle();
    }

    private function getChildClassesOtherThanSiteTree()
    {
        $childClasses = Config::inst()->get(get_class($this->owner), 'allowed_children');
        return array_values(array_filter($childClasses, function ($className) {
            return $className !== SiteTree::class;
        }));
    }
}
