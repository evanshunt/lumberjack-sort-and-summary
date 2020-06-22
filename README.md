# Lumberjack Sort and Summary

A common use case for [SilverStripe's Lumberjack module](https://github.com/silverstripe/silverstripe-lumberjack) is [the holder/page pattern](https://www.silverstripe.org/learn/lessons/v4/the-holderpage-pattern-1). In this case, the holder page will only have one type of child page.

Because Lumberjack is built flexible enought to accommodate multiple types of child pages, some features that we might expect when only a single page type is allowed are missing. Such as:

* Using the default sort order for the child page type
* Listing the pages with the defined summary fields
* Labeling the listings with the name of the name of the child type instead of "Child Pages"

This module applies the above when a single child type is defined for the parent holder page.

*Thanks to [@bcairns](https://github.com/bcairns) for the code and documentation this module was based on*

## Installation

`composer install evanshunt/lumberjack-sort-and-summary`

## Usage

Define `$allowed_children` for the parent page. (Can be done either in yml config or model)

```
namespace MyNamespace;

use Page;

class StoryLandingPage extends Page
{
    private static $allowed_children = [
        StoryDetailsPage::class
    ];
}
```

Define `$plural_name`, `$summary_fields`, and `$default_sort` for child page.

```
namespace MyNamespace;

use Page;

class StoryDetailsPage extends Page
{
    private static $table_name = 'StoryDetails';
    private static $singular_name = 'Story';
    private static $plural_name = 'Stories';

    private static $can_be_root = false;
    private static $default_parent = StoryLandingPage::class;

    private static $db = [
        'PublicationDate' => 'Date',
    ];

    private static $summary_fields = [
        'Title',
        'PublicationDate'
    ];

    private static $default_sort = 'PublicationDate DESC';
}
```

Install and configure Lumberjack

```
MyNamespace\StoryDetailsPage:
  show_in_sitetree: false
MyNamespace\StoryLandingPage:
  extensions:
    - SilverStripe\Lumberjack\Model\Lumberjack
```

## Configuration

No additional configuration is needed. This module overloads methods in Lumberjack itself, to check the value of `$allowed_children` on the parent class, and if only one class is defined, it will apply the changes based on the config values in the child class.
