<?php
/*
	Copyright (c) 2014, Zhaofeng Li
	All rights reserved.
	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:
	* Redistributions of source code must retain the above copyright notice, this
	list of conditions and the following disclaimer.
	* Redistributions in binary form must reproduce the above copyright notice,
	this list of conditions and the following disclaimer in the documentation
	and/or other materials provided with the distribution.
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
	IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
	FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
	DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
	SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
	CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
	OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
	OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*
	Standalone Link Handler

	This LinkHandler fetches the webpage, then parses the
	Metadata using the MetadataParsers
*/

namespace Reflinks\LinkHandlers;

use Reflinks\LinkHandler;
use Reflinks\Spider;
use Reflinks\Metadata;
use Reflinks\MetadataParserChain;
use Reflinks\Exceptions\LinkHandlerException;
use Masterminds\HTML5;

class StandaloneLinkHandler extends LinkHandler {
	private $spider = null;

	const ERROR_UNKNOWN = 0;
	const ERROR_FETCH = 1;
	const ERROR_HTTPERROR = 2;
	const ERROR_EMPTY = 3; 

	function __construct( Spider $spider ) {
		global $config;
		$this->spider = $spider;
		$this->chain = new MetadataParserChain( $config['parserchain'] );
	}

	public function getMetadata( $url ) {
		// Fetch the webpage
		$response = $this->spider->fetch( $url );
		if ( !$response->successful ) { // failed
			throw new LinkHandlerException( "Fetching error", self::ERROR_FETCH );
		} elseif ( $response->header['http_code'] != 200 ) { // http error
			throw new LinkHandlerException( "HTTP Error: " . $response->header['http_code'], self::ERROR_HTTPERROR, $response->header );
		} elseif ( empty( $response->html ) ) { // empty response
			throw new LinkHandlerException( "Empty response", self::ERROR_EMPTY );	
		}

		// Extract the metadata
		$metadata = new Metadata();
		$metadata->url = $url;
		$html5 = new HTML5();
		$dom = $html5->loadHTML( $response->html );
		$metadata = $this->chain->parse( $dom, $metadata );
		return $metadata;
	}

	public static function explainErrorCode( $code ) {
		switch ( $code ) {
			default:
			case self::ERROR_UNKNOWN:
				return "Unknown error";
			case self::ERROR_FETCH:
				return "Fetching error";
			case self::ERROR_HTTPERROR:
				return "HTTP Error";
			case self::ERROR_EMPTY:
				return "Empty document";
		}
	}
}
