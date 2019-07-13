<?php
namespace MediaWiki\Extension\SyntaxHighlightPages;

class Content extends \TextContent {
	// Must match the name used in the 'ContentHandlers' section of extension.json
	const MODEL = 'syntaxhighlight';

	public function __construct( $text, $model_id = self::MODEL ) {
		parent::__construct( $text, $model_id );
	}

	protected function fillParserOutput(
		\Title $title, $revId, \ParserOptions $options, $generateHtml, \ParserOutput &$output
	){
		$parts = explode('.', $title->getDBkey());
		$status = \SyntaxHighlight::highlight( $this->mText, end($parts));
		if ( !$status->isOK() ) {
			return true;
		}

		$output->addModuleStyles( 'ext.pygments' );
		$output->setText( '<div dir="ltr">' . $status->getValue() . '</div>' );
	}
}

