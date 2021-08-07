<?php declare(strict_types=1);
namespace oldex;

class SearchEngine
{
	private $DDGURL;
	
	function __construct() 
	{
		$this->DDGURL = "https://html.duckduckgo.com/html?q=";
	}

	private function clean_str($str): String {
		// This function replaces unicode characters that old machines
		// can't handle
		$str = str_replace( "‘", "'", $str );
		$str = str_replace( "’", "'", $str );
		$str = str_replace( "“", '"', $str );
		$str = str_replace( "”", '"', $str );
		$str = str_replace( "–", '-', $str );
		$str = str_replace( "&#x27;", "'", $str );

		return $str;
	}

	function searchWithDDG(string $input): Array
	{
		// Searching with DuckDuckGo
		// Original idea and code was made by Sean from Action Retro

		$result_html = NULL;
		if(!$result_html = file_get_contents($this->DDGURL . $input))
			return Array("status" => "error", "error_code" => "100", "url" => $this->DDGURL . $input);

		// Parsing the DuckDuckGo search result
		$result_html = str_replace('strong>', 'b>', $result_html);
		$result_html = str_replace('em>', 'i>', $result_html);
		$result_html = $this->clean_str($result_html);

		// Parsing the blocks
		$result_blocks = explode('<h2 class="result__title">', $result_html);
		$result_blocks_count = count($result_blocks)-1;

		$returnArray = Array('status' => 'ok', 'result' => array());

		for ($i = 1; $i <= $result_blocks_count; $i++)
		{
			if(strpos($result_blocks[$i], '<a class="badge--ad">') === false)
			{
				// Parsing the link
				$block['link'] = explode('class="result__a" href="', $result_blocks[$i])[1];
				$block_link_tmp = explode('">', $block['link']);
				$block['link'] = str_replace('//duckduckgo.com/l/?uddg=', null, $block_link_tmp[0]);
				$block['link'] = explode('&amp', urldecode($block['link']))[0];

				// Parsing the block title
				$block['title'] = str_replace('</a>', null, explode("\n", $block_link_tmp[1]))[0];
				
				// Parsing the Display URL
				$block['display_url'] = explode('class="result__url"', $result_blocks[$i])[1];
				$block['display_url'] = trim(explode("\n", $block['display_url'])[1]);

				// Parsing the page snippet
				$block['snippet'] = explode('class="result__snippet"', $result_blocks[$i])[1];
				$block['snippet'] = explode('">', $block['snippet'])[1];
				$block['snippet'] = explode('</a>', $block['snippet'])[0];
				
				// Adding a search result to array
				$returnArray['result'][] = array('title' => $block['title'], 'link' => $block['link'], 'snippet' => $block['snippet'], 'display_url' => $block['display_url']);
			}
		}

		return $returnArray;
	}
}
