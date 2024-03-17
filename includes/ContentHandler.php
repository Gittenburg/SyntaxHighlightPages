<?php
namespace MediaWiki\Extension\SyntaxHighlightPages;

class ContentHandler extends \TextContentHandler {

	public function __construct( $modelId = Content::MODEL ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_TEXT ] );
	}

	public static function getExtensionMap() {
		// Turn e.g, [ 'foo', 'bar' => 'foo' ] to [ 'foo' => 'foo', 'bar' => 'foo' ]
		//
		// Need to do this transform as relying on keys instead (e.g. checking
		// isset(wgSyntaxHighlightPagesSuffixes[$ext])) gives a surprising
		// positive for e.g. 'Foo.0' because PHP casts the '0' to 0, which is a
		// valid key if there are any non-mappped values.
		//
		// Also prevents [ 'foo' => 'bar' ] from enabling 'bar' as an extension
		// which may not be desired.
		global $wgSyntaxHighlightPagesSuffixes;
		$map = array();
		foreach ($wgSyntaxHighlightPagesSuffixes as $k => $v) {
			$map[gettype($k) === "integer" ? $v : $k] = $v;
		}
		return $map;
	}

	protected function getContentClass() {
		return Content::class;
	}

	protected function fillParserOutput(
		\Content $content, \MediaWiki\Content\Renderer\ContentParseParams $cpoParams, \ParserOutput &$output
	){
		$title = $cpoParams->getPage()->getDBkey();
		$parts = explode('.', $title);
		$ext = end($parts);
		$map = ContentHandler::getExtensionMap();
		$lang = isset($map[$ext]) ? $map[$ext] : "";
		$status = \MediaWiki\SyntaxHighlight\SyntaxHighlight::highlight( $content->getText(), $lang );
		if ( !$status->isOK() ) {
			return true;
		}
		$output->addModuleStyles([ 'ext.pygments' ]);
		$output->setText( '<div dir="ltr">' . $status->getValue() . '</div>' );
	}
}
