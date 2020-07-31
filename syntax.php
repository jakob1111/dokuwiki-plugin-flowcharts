<?php
/**
 * DokuWiki Plugin flowcharts (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Jakob Schwichtenberg <mail@jakobschwichtenberg.com>
 */

use dokuwiki\Parsing\Parser;

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
    function getType(){ return 'protected'; }

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
    function handle($match, $state, $pos, Doku_Handler $handler){
        switch ($state) {
            case DOKU_LEXER_ENTER:
                return array($state, '');

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
    function render($mode, Doku_Renderer $renderer, $indata) {
        if($mode == 'xhtml'){
            list($state, $match) = $indata;
            switch ($state) {

            case DOKU_LEXER_ENTER :
                  // securityLevel loose allows more advanced functionality such as subgraphs to run.
                  // @todo: this should be an option in the interface.
                  $renderer->doc .= '<div class="mermaid">';
                  break;
              case DOKU_LEXER_UNMATCHED :
                  $instructions = $this->p_get_instructions($match);
                  $xhtml = $this->p_render($instructions);
                  $renderer->doc .= $xhtml;
                  break;
              case DOKU_LEXER_EXIT :
                  $renderer->doc .= "</div>";
                  break;
            }
            return true;
        }
        return false;
    }

    /*
     * Get the parser instructions siutable for the mermaid
     *
     */
    function p_get_instructions($text) {

        $modes = array();

        // add default modes
        $std_modes = array('internallink', 'media','externallink');

        foreach($std_modes as $m){
            $class = 'dokuwiki\\Parsing\\ParserMode\\'.ucfirst($m);
            $obj   = new $class();
            $modes[] = array(
                'sort' => $obj->getSort(),
                'mode' => $m,
                'obj'  => $obj
            );
        }

        // add formatting modes
        $fmt_modes = array('strong','emphasis','underline','monospace',
                           'subscript','superscript','deleted');
        foreach($fmt_modes as $m){
            $obj   = new \dokuwiki\Parsing\ParserMode\Formatting($m);
            $modes[] = array(
                'sort' => $obj->getSort(),
                'mode' => $m,
                'obj'  => $obj
            );
        }

        // Create the parser and handler
        $Parser = new Parser(new Doku_Handler());

        //add modes to parser
        foreach($modes as $mode){
            $Parser->addMode($mode['mode'],$mode['obj']);
        }

        // Do the parsing
        $p = $Parser->parse($text);

        return $p;
    }

    public function p_render($instructions) {
        $Renderer = p_get_renderer('flowcharts');

        $Renderer->smileys = getSmileys();
        $Renderer->entities = getEntities();
        $Renderer->acronyms = getAcronyms();
        $Renderer->interwiki = getInterwiki();

        // Loop through the instructions
        foreach ($instructions as $instruction) {
            // Execute the callback against the Renderer
            if(method_exists($Renderer, $instruction[0])){
                call_user_func_array(array(&$Renderer, $instruction[0]), $instruction[1] ? $instruction[1] : array());
            }
        }

        return $Renderer->doc;
    }

}

