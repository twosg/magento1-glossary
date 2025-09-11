<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Glossary
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Glossary_Block_Word extends Mage_Core_Block_Template
{
	/**
	 * Retrieve the current word
	 *
	 * @return null|Fishpig_Glossary_Model_Word
	 */
	public function getWord()
	{
		if (!$this->hasWord()) {
			$this->setWord(Mage::registry('glossary_word'));
		}
		
		return $this->_getData('word');
	}
	
	/**
	 * Get glossary index URL
	 *
	 * @return string
	 */
	public function getGlossaryUrl()
	{
		return Mage::helper('glossary')->getIndexUrl();
	}
	
	/**
	 * Generate JSON-LD structured data for Article + DefinedTerm
	 *
	 * @return string
	 */
	public function getWordArticleJsonLd(): string
	{
		$word = $this->getWord();
		if (!$word) {
			return '';
		}
		
		$helper = Mage::helper('glossary');
		$organizationData = $helper->getOrganizationData();
		
		$jsonLd = [
			'@context' => 'https://schema.org',
			'@type' => 'Article',
			'mainEntityOfPage' => [
				'@type' => 'WebPage',
				'@id' => $word->getUrl()
			],
			'headline' => 'Was ist ' . $word->getWord() . '?',
			'author' => $organizationData,
			'publisher' => $organizationData,
			'about' => [
				'@type' => 'DefinedTerm',
				'name' => $word->getWord(),
				'description' => $word->getMetaDefinition()
			]
		];
		
		return json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
}
