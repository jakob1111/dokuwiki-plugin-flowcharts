<?php
/**
 * DokuWiki Plugin flowcharts (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Jakob Schwichtenberg <mail@jakobschwichtenberg.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}
/**
* if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
* if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
* require_once(DOKU_PLUGIN.'syntax.php');
*/

class syntax_plugin_flowcharts extends DokuWiki_Syntax_Plugin
{
    function getType(){ return 'container'; }
    function getPType(){ return 'normal'; }
    function getAllowedTypes() { 
        return array('container','substition','protected','disabled','formatting','paragraphs');
    }
 
    // must return a number lower than returned by native 'code' mode (200)
    function getSort(){ return 158; }

    
    

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
     function connectTo($mode) {       
        $this->Lexer->addEntryPattern('<flow>(?=.*?</flow>)',$mode,'plugin_flowcharts');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('</flow>','plugin_flowcharts');
    }


    /**
     * Handle matches of the flowcharts syntax
     */
    function handle($match, $state, $pos, &$handler){
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $data = strtolower(trim(substr($match,6,-1)));
                return array($state, $data);
 
            case DOKU_LEXER_UNMATCHED : 
                return array($state, $match);
 
            case DOKU_LEXER_EXIT :
                return array($state, '');
 
        }       
        return false;
    }


    /**
     * Render xhtml output or metadata
     */
    function render($mode, &$renderer, $indata) {
        if($mode == 'xhtml'){
            list($state, $match) = $indata;
            switch ($state) {
 
            case DOKU_LEXER_ENTER :      
                $renderer->doc .= '</p><div class="mermaid">';
                break;
 
              case DOKU_LEXER_UNMATCHED : 
                $renderer->doc .= $match;
                break;
 
              case DOKU_LEXER_EXIT :
                $renderer->doc .= "</div><p>";
                break;
            }
            return true;
        }
        return false;
    }

}

