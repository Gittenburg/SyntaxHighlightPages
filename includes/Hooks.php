<?php
namespace MediaWiki\Extension\SyntaxHighlightPages;

class Hooks {
	public static function onContentHandlerDefaultModelFor( \Title $title, &$model ) {
		global $wgSyntaxHighlightPagesSuffixes;
		$parts = explode('.', $title->getDBkey());
		if ($title->isContentPage() && in_array(end($parts), $wgSyntaxHighlightPagesSuffixes)){
			$model = Content::MODEL;
			return false;
		}
		return true;
	}

	public static function onBaseTemplateToolbox( \BaseTemplate $baseTemplate, array &$toolbox ) {
		$title = $baseTemplate->getSkin()->getTitle();
		if ($title->getContentModel() == Content::MODEL){
			$toolbox['download'] = ['text'=>'Download', 'href'=>$title->getLocalURL('action=raw'), 'download'=>$title->getSubpageText()];
		}
	}
}
