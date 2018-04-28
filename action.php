<?php
/**
 * DokuWiki Plugin flowcharts (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Jakob Schwichtenberg <mail@jakobschwichtenberg.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class action_plugin_flowcharts extends DokuWiki_Action_Plugin
{

 public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this,
                                   '_loadmermaid');
    }
 
    public function _loadmermaid(Doku_Event $event, $param) {
        $event->data['script'][] = array(
                            'type'    => 'text/javascript',
                            'charset' => 'utf-8',
                            '_data'   => '',
                            'src'     => 'https://cdn.rawgit.com/knsv/mermaid/7.0.0/dist/mermaid.min.js');
        $event->data['link'][] = array(
                           'rel'    => 'stylesheet',
                        'type' => 'text/css',
                            'href'     => 'https://cdn.rawgit.com/knsv/mermaid/7.0.0/dist/mermaid.css');
    }

}

