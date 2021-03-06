<?php
/*
	This is the default configuation of Reflinks.

	Please don't edit this file, unless you want to change the 
	default configuations and submit it to the code repository.

	To override these configuations, create a file named "config.php" under "/config"
*/

use Reflinks\UserOptionsProvider;

// The useragent used when fetching web pages and accessing MediaWiki API
$config['useragent'] = "Reflinks/1.0 (by Zhaofeng Li: https://en.wikipedia.org/wiki/User:Zhaofeng_Li/Reflinks )";

// Wiki map
$config['wikis'] = array(
	"wikipedia" => array(
		"identifiers" => array(
			"en", "simple", "zh",
		),
		"api" => "https://%id%.wikipedia.org/w/api.php",
		"indexphp" => "https://%id%.wikipedia.org/w/index.php",
	),
);

// Link handler
$config['linkhandler'] = "Reflinks\LinkHandlers\StandaloneLinkHandler";

// Metadata parser chain
$config['parserchain'] = array(
	"TitleMetadataParser",
	"OpenGraphMetadataParser",
	"SchemaOrgMetadataParser",
	"MetaTagMetadataParser",
	"WaybackMachineFixerMetadataParser",
	"PublisherFixerMetadataParser",
	"BadTitlesFixerMetadataParser",
	"FixerMetadataParser",
);

// Stuff to insert into the end of a cite template
$config['citeextra'] = "";

// Override localised default edit summary
// Use %numfixed% to show how many references are fixed, and %numskipped% for skipped.
// Setting this will /OVERRIDE/ the I18N version!
// $config['summary'] = "Filled in %numfixed% bare reference(s) with [[User:Zhaofeng Li/Reflinks]]";

// Extra information to append to the default edit summary
// This will be added to the end of the default edit summary
// $config['summaryextra'] = "";

// Whether to enable the filter or not
$config['spam']['enable'] = false;

// A path to the /local/ blacklist file, used by Extension:SpamBlacklist on MediaWiki.
$config['spam']['file'] = __DIR__ . "/blacklist";

// The actual array for blacklist regexes, for reference only. You should not set this one manually.
$config['spam']['blacklist'] = array();

// The maximum execution time of result.php, in seconds
$config['maxtime'] = 300;

// Path to Cacycle's wDiff script. For easy offline development, download a copy to scripts/diff.js which is ignored by git.
$config['wdiff'] = "https://en.wikipedia.org/w/index.php?title=User:Cacycle/diff.js&action=raw&ctype=text/javascript";

// Intuition i18n
$config['i18n']['intuition'] = __DIR__ . "/Intuition/ToolStart.php";
$config['i18n']['domainfile'] = __DIR__ . "/Reflinks.i18n.php";
$config['i18n']['domain'] = "reflinks";

// User-settable options
$config['options'] = array(
	"page" => array(
		"type" => UserOptionsProvider::TYPE_SPECIAL,
		"name" => "Page name",
	),
	"text" => array(
		"type" => UserOptionsProvider::TYPE_SPECIAL,
		"name" => "Raw wikitext",
	),
	// TODO: Implement this correctly
	"wiki" => array(
		"type" => UserOptionsProvider::TYPE_SPECIAL,
		"name" => "Wiki",
	),
	"plainlink" => array(
		"type" => UserOptionsProvider::TYPE_CHECKBOX,
		"advanced" => false,
		"default" => false,
	),
	"noremovetag" => array(
		"type" => UserOptionsProvider::TYPE_CHECKBOX,
		"advanced" => false,
		"default" => false,
	),
	"nowatch" => array(
		"type" => UserOptionsProvider::TYPE_CHECKBOX, // This is automatically set by wikitoolbox.js
		"advanced" => true,
		"default" => false,
	),
	"addblankmetadata" => array(
		"type" => UserOptionsProvider::TYPE_CHECKBOX,
		"advanced" => false,
		"default" => false,
	),
	"noaccessdate" => array(
		"type" => UserOptionsProvider::TYPE_CHECKBOX,
		"advanced" => false,
		"default" => true,
	),
	"usedomainaswork" => array(
		"type" => UserOptionsProvider::TYPE_CHECKBOX,
		"advanced" => false,
		"default" => false,
	),
);

date_default_timezone_set( "UTC" );

@include __DIR__ . "/../config/config.php";
