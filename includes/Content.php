<?php
namespace MediaWiki\Extension\SyntaxHighlightPages;

class Content extends \TextContent {
	// Must match the name used in the 'ContentHandlers' section of extension.json
	const MODEL = 'syntaxhighlight';

	public function __construct( $text, $model_id = self::MODEL ) {
		parent::__construct( $text, $model_id );
	}
}

